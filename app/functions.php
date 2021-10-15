<?php

use Illuminate\Database\Eloquent\Model;
use App\Foundation\Tire;
use Illuminate\Support\Facades\Cache;
use App\Model\Admin\SensitiveWord;
use App\Model\Admin\Config as SiteConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * 生成随机code
 * @param int $length
 * @return string
 */
function createCode($length = 12)
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($string) - 1;
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $string[mt_rand(0, $max)];
    }
    return $token;
}

/**
 * 直接从数据库获取系统后台配置
 *
 * @param string $key key
 * @param mixed $default key不存在时的默认值
 * @return mixed key对应的value
 */
function getConfig($key, $default = null)
{
    $v = SiteConfig::where('key', $key)->value('value');
    return !is_null($v) ? $v : $default;
}

function getLang($local)
{
    if ($local == "en") {
        $name = "top menu";
    } elseif ($local == "my") {
        $name = "top menu";
    } else {
        $name = "顶级菜单";
    }
    return $name;
}

/**
 * @Title: getRandChar
 * @Description: 生成随机字符串函数
 * @param $length
 * @return null|string
 * @Author: 李军伟
 * @date: 2018/7/18 12:10
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

function parseEntityFieldParams($params)
{
    if (strpos($params, 'getFormItemsFrom') === 0 && function_exists($params)) {
        $params = call_user_func($params);
    }

    $items = explode("\n", $params);
    return array_map(function ($item) {
        return explode("=", $item);
    }, $items);
}

function isChecked($value, $options)
{
    return in_array($value, explode(',', $options), true);
}

function xssFilter(Model $data)
{
    $attributes = $data->getAttributes();
    foreach ($attributes as &$v) {
        if (is_string($v)) {
            $v = htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
        }
    }
    $data->setRawAttributes($attributes);
}

function initTire()
{
    return Cache::rememberForever('sensitive_words_tire', function () {
        $tires = [];

        foreach (['noun', 'verb', 'exclusive'] as $v) {
            $words = SensitiveWord::query()->select($v)->where($v, '<>', '')->get();

            $tire = new Tire();
            foreach ($words as $k) {
                $tire->add($k->$v);
            }
            $tires[$v] = $tire;
        }

        return $tires;
    });
}

function initTireSingle()
{
    return Cache::rememberForever('sensitive_words_tire_single', function () {
        $types = SensitiveWord::query()->select('type')->groupBy('type')->get();
        $tire = new Tire();
        foreach ($types as $type) {
            $words = SensitiveWord::query()->where('type', $type->type)->get();
            $nouns = [];
            $verbs = [];
            $exclusives = [];
            foreach ($words as $word) {
                if ($word->noun !== '') {
                    $nouns[] = $word->noun;
                } elseif ($word->verb !== '') {
                    $verbs[] = $word->verb;
                } elseif ($word->exclusive !== '') {
                    $exclusives[] = $word->exclusive;
                }
            }

            foreach ($exclusives as $k) {
                $tire->add($k);
            }
            foreach ($verbs as $vk) {
                foreach ($nouns as $nk) {
                    $tire->add($vk . $nk);
                }
            }
        }

        return $tire;
    });
}

function mapTypeToVerbOfSensitiveWords()
{
    return Cache::rememberForever('sensitive_verb_words', function () {
        $words = SensitiveWord::query()->select('verb', 'type')->where('verb', '<>', '')->get();

        $data = [];
        foreach ($words as $word) {
            $data[$word->type <> '' ? $word->type : 'others'][] = $word->verb;
        }

        return $data;
    });
}

/**
 * 敏感词检查
 *
 * @param string $text 待检查文本
 * @param string $type 名词、动词的检测方法。默认为 join 。join：名词和动词相连组合在一起视为违规 all：名词和动词只要同时出现即为违规
 * @param mixed $mode 检查模式。仅 $type 为 all 时有效。默认名词、动词、专用词都检查，显示可指定为 noun verb exclusive
 * @return array
 */
function checkSensitiveWords(string $text, $type = 'join', $mode = null)
{
    if (!is_null($mode) && !in_array($mode, ['noun', 'verb', 'exclusive'])) {
        throw new \InvalidArgumentException('mode参数无效，只能为null值、noun、exclusive');
    }

    if ($type === 'join') {
        $tire = initTireSingle();
        $result = $tire->seek($text);
        return $result;
    }

    $tires = initTire();
    if (!is_null($mode)) {
        return $tires[$mode]->seek($text);
    }

    $result = [];
    $return = [];
    foreach ($tires as $k => $tire) {
        $result[$k] = $tire->seek($text);
    }
    if (!empty($result['noun']) && !empty($result['verb'])) {
        $data = mapTypeToVerbOfSensitiveWords();
        foreach ($result['noun'] as $noun) {
            $type = Cache::rememberForever('sensitive_words_noun_type:' . $noun, function () use ($noun) {
                return SensitiveWord::query()->where('noun', $noun)->value('type');
            });
            $type = $type ? $type : 'others';
            $verbs = array_intersect($data[$type], $result['verb']);
            if (!empty($verbs)) {
                array_push($verbs, $noun);
                $return[] = implode(' ', $verbs);
            }
        }
    }
    return array_merge($return, $result['exclusive']);
}

/**
 * @Title: order_no
 * @Description: 订单号的生成规则
 * @return string
 * @Author: 李军伟
 */
function order_no()
{
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[intval(date('Y')) - date("Y", time())] .
        strtoupper(dechex(date('m'))) .
        date('d') . substr(time(), -5) .
        substr(microtime(), 2, 5) .
        sprintf('%02d', rand(0, 99));
    return $orderSn;
}

/**
 * @Title: get_extension
 * @Description: 获取文件后缀
 * @param $file
 * @return bool|string
 * @Author: 李军伟
 */
function get_extension($file)
{
    return substr(strrchr($file, '.'), 1);
}

/**
 * @Title: send_email
 * @Description: 邮件发送
 * @param $to 接收人
 * @param string $subject 邮件标题
 * @param string $content 邮件内容(html模板渲染后的内容)
 * @return array
 * @Author: 李军伟
 */
function send_email($to, $subject = '', $content = '')
{
    //判断openssl是否开启
    $openssl_funcs = get_extension_funcs('openssl');
    if (!$openssl_funcs) {
        return array('status' => -1, 'msg' => '请先开启openssl扩展');
    }
    $mail = new PHPMailer;
    //配置信息
    if (empty(env('MAIL_HOST')) || empty(env('MAIL_PORT')) || empty(env('MAIL_USERNAME')) || empty(env('MAIL_PASSWORD'))) {
        return array("error" => 1, "message" => '邮箱配置不完整');
    }

    $mail->CharSet = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    //调试输出格式
    //$mail->Debugoutput = 'html';
    //smtp服务器
    $mail->Host = env('MAIL_HOST');
    //端口 - likely to be 25, 465 or 587
    $mail->Port = env('MAIL_PORT');

    if ($mail->Port == 465) $mail->SMTPSecure = 'ssl';// 使用安全协议
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //用户名
    $mail->Username = env('MAIL_USERNAME');
    //密码
    $mail->Password = env('MAIL_PASSWORD');
    //Set who the message is to be sent from
    $mail->setFrom(env('MAIL_USERNAME'));
    //回复地址
    $mail->addReplyTo(env('MAIL_USERNAME'), 'hot-tv');
    //设置抄送人
    //$mail->addCC($config['']);
    //密送者，Mail Header不会显示密送者信息
    //$mail->addBCC($config['']);

    //接收邮件方
    if (is_array($to)) {
        foreach ($to as $v) {
            $mail->addAddress($v);
        }
    } else {
        $mail->addAddress($to);
    }
    $mail->isHTML(true);// send as HTML
    //标题
    $mail->Subject = $subject;
    //HTML内容转换
    $mail->msgHTML($content);
    //Replace the plain text body with one created manually
    //$mail->AltBody = 'This is a plain-text message body';
    //添加附件 (填绝对路径) 可以设定名字
    //$mail->addAttachment('images/phpmailer_mini.png', 'name.jpg');
    //send the message, check for errors
    try {
        if ($mail->send()) ;
        return array('status' => 1, 'msg' => '邮件发送成功');
    } catch (Exception $e) {
        return array('status' => -1, 'msg' => '发送失败: ' . $mail->ErrorInfo);
    }
}

/**
 * @Title: excelExport
 * @Description: excel表格导出
 * @param string $fileName 文件名称
 * @param array $headArr 表头名称
 * @param array $data 要导出的数据
 * @Author: 李军伟
 */
function excelExport($fileName = '', $headArr = [], $data = [])
{
    $fileName .= "_" . date("Y_m_d", time()) . ".xls";
    $objExcel = new \PHPExcel();
    $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
    $objExcel->getProperties();
    $key = ord("A");  // 设置表头

    foreach ($headArr as $v) {
        $colum = chr($key);
        $objExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $objExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objExcel->getActiveSheet();
    foreach ($data as $key => $rows) {
        $span = ord("A");
        foreach ($rows as $keyName => $value) {
            $objActSheet->setCellValue(chr($span) . $column, $value);
            $span++;
        }
        $column++;
    }

    $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
    $objExcel->setActiveSheetIndex(0);  // 设置活动单指数到第一个表，所以Excel打开这是第一个表
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='$fileName'");
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output'); // 文件通过浏览器下载
}

function httpUrl($url, $params = false, $ispost = 0, $header = array(), $filename, $verify = false)
{
    $httpInfo = array();
    $ch = curl_init();
    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //忽略ssl证书
    if ($verify === true) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if (is_array($params)) {
            $params = http_build_query($params);
        }
        if ($params) {
            file_put_contents($filename, $url . '?' . $params);
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    $response = curl_exec($ch);
    if ($response === FALSE) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        dd($httpInfo);
        return false;
    }
    curl_close($ch);
    return $response;
}

/**
 * 冒泡排序
 * @param  Array 排序数组
 * @return Array 排序号的数组
 */
function bubbleSort($arr)
{
    $len = count($arr);
    //该层循环控制 需要冒泡的轮数
    for ($i = 1; $i < $len; $i++) { //该层循环用来控制每轮 冒出一个数 需要比较的次数
        for ($k = 0; $k < $len - $i; $k++) {
            if ($arr[$k] > $arr[$k + 1]) {
                $tmp = $arr[$k + 1];
                $arr[$k + 1] = $arr[$k];
                $arr[$k] = $tmp;
            }
        }
    }
    return $arr;
}

/**
 * @Title: nonceStr
 * @Description: 生成6位随机数
 * @return string
 * @Author: 李军伟
 */
function nonceStr()
{
    static $seed = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    $str = '';
    for ($i = 0; $i < 6; $i++) {
        $rand = rand(0, count($seed) - 1);
        $temp = $seed[$rand];
        $str .= $temp;
        unset($seed[$rand]);
        $seed = array_values($seed);
    }
    return $str;
}

// 生成6位不重复的随机数
function rand_code($length = 6)
{
    $chars = "1234567890";
    $str = "";
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, $size - 1)];
    }
    return $str;
}

function dates($date)
{
    $timestamp = strtotime( $date );
    $start_time = date( 'Y-m-1 00:00:00', $timestamp );
    $mdays = date( 't', $timestamp );
    $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', $timestamp );

    return ['start_time' => $start_time, 'end_time' => $end_time];
}
