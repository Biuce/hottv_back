<?php
/**
 * Created by PhpStorm.
 * Project: IAuth.php
 * User: admin
 * Date: 2020/3/2
 * Time: 17:18
 */

namespace App\Api\General;

use App\Api\Helpers\ApiResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class IAuth
{
    use ApiResponse;

    /**
     * 生成每次请求的sign
     *
     * @param array $data
     * @return string
     */
    public static function setSign($data = [])
    {
        // 1 按字典排序
        ksort($data);
        // 2拼接字符串数据  &
        $string = http_build_query($data);
        // 3通过aes来加密
        $string = (new Aes())->encrypt($string);

        return $string;
    }

    /**
     * @Title: checkSignPass
     * @Description: 检查sign是否正常
     * @param $data
     * @return bool
     * @throws ValidationException
     * @Author: 李军伟
     */
    public function checkSignPass($data)
    {
        $str = (new Aes())->decrypt($data['sign'][0]);
        if (empty($str)) {
            throw ValidationException::withMessages([trans('interface.sign_fail')])->status(400);
        }
        //parse_str()把diid=xx&app_type=android转化成数组并赋值给第2个参数
        parse_str($str, $arr);
        if (!is_array($arr) || empty($arr['log-phone-id']) || $arr['log-phone-id'] != $data['log-phone-id'][0] || $arr['log-system-version'] != $data['log-system-version'][0]) {
            throw ValidationException::withMessages([trans('interface.parameter_not_complete')])->status(400);
        }
        //应用模式下调用（应用上线调用，开发测试跳过）
        if (env('APP_DEBUG') === false) {
            //检验有sign是否过期
            //注：客户端和服务端要求时间一致，
            //解决方案：客户端请求服务端api/time/index接口获取服务器时间，客户端根据实际情况 + - 时间值
            if ((time() - ceil($arr['time'] / 1000)) > env('APP_SIGN_TIME')) {
                throw ValidationException::withMessages([trans('interface.sign_dated')])->status(400);
            }

            //检测sign唯一性
            if (Redis::get($data['sign'])) {
                throw ValidationException::withMessages([trans('interface.sign_used')])->status(400);
            }
        }

        return true;
    }

    /**
     * 设置登录的token  - 唯一性的
     *
     * @param string $phone
     * @return string
     */
    public static function setAppLoginToken($phone = '')
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str . $phone);
        return $str;
    }

}