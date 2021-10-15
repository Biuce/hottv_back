<?php
/**
 * Created by PhpStorm.
 * Project: Auth.php
 * User: admin
 * Date: 2020/3/2
 * Time: 17:08
 */

namespace App\Api\General;

use App\Api\Helpers\ApiResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class Auth
{
    use ApiResponse;

    public $headers = '';
    public $lang = [];
    public $types = ['android', 'ios'];

    /**
     * @Title: checkRequestAuth
     * @Description: 检查每次app请求的数据是否合法
     * @param $headers
     * @throws ValidationException
     * @Author: 李军伟
     */
    public function checkRequestAuth($headers)
    {
        if (empty($headers['lang'][0])) {
            throw ValidationException::withMessages([trans('interface.lang')])->status(400);
        }

        // 基础参数校验
        if (empty($headers['sign'][0])) {
            throw ValidationException::withMessages([trans('interface.sign')])->status(400);
        }
//        if (!in_array($headers['type'][0], $this->types)) {
//            throw ValidationException::withMessages([trans('interface.type')])->status(400);
//        }
        // 需要sign
//        if (!(new IAuth())->checkSignPass($headers)) {
//            throw ValidationException::withMessages([trans('interface.auth')])->status(400);
//        }
        Redis::set($headers['sign'][0], 1, env('APP_SIGN_CACHE_TIME'));

        // 1、文件  2、mysql 3、redis
        $this->headers = $headers;
    }

    /**
     * @Title: testAes
     * @Description: 测试加密sign
     * @Author: 李军伟
     * @date: 2018/7/18 10:57
     */
    public function testAes()
    {
        $data = [
            'log-phone-model' => 'Hi3798MV200',
            'log-system-version' => '7.0',
            'log-phone-id' => 'c9d16218dd9363458005337effc067aa',
            'version' => 1,
            'type' => 'android',
            'lang' => 'zh_CN',
            'time' => $this->get13TimeStamp(),
//            'time' => '1539755591103',
        ];

        //        $str = 's3nQyV8+QlRQGafLYAON1q0h+w+8Hwf4shcEPQza4ZQp2ge5NAyLo4jjsJKwRDgt0pL0v/F3dlnxj3AX4xyXTg==';
        // s3nQyV8+QlRQGafLYAON1q0h+w+8Hwf4shcEPQza4ZQp2ge5NAyLo4jjsJKwRDgt0pL0v/F3dlnxj3AX4xyXTg==
        echo IAuth::setSign($data);
        exit;
        echo (new Aes())->decrypt($str);
        exit;
    }

    /**
     * [get13TimeStamp  获取13位时间戳]
     * @author [忘尘]
     * @return int
     */
    public function get13TimeStamp()
    {
        list($t1, $t2) = explode(' ', microtime());

        //$t1=1515563772   $t2 =0.01694200
        return $t2 . ceil($t1 * 1000);
    }
}