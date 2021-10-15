<?php
/**
 * Created by PhpStorm.
 * Project: Aes.php
 * User: admin
 * Date: 2020/3/2
 * Time: 17:19
 */

namespace App\Api\General;


class Aes
{
    private $key = null;
    private $hex_iv = null;

    /**
     * Aes constructor.
     */
    public function __construct()
    {
        // 在配置文件app.php中定义aesKey
        $this->key = env('AES_KEY');
        $this->key = hash('sha256', $this->key, false);
        $this->hex_iv = env('AES_HEXIV');
    }

    /**
     * 加密方式
     *
     * @param $input
     * @return string
     */
    public function encrypt($input)
    {
//        $data = openssl_encrypt($input, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hexToStr($this->hex_iv));
        $data = openssl_encrypt($input, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hex_iv);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * 解密方法
     *
     * @param $input
     * @return string
     */
    public function decrypt($input)
    {
        $decrypted = openssl_decrypt(base64_decode($input), 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hex_iv);
        return $decrypted;
    }


    /**
     * 解密方法
     *
     * @param $input
     * @return string
     */
    public function decrypt_2($input)
    {
        $decrypted = openssl_decrypt($input, 'AES-128-CBC', 'long.tv_k2na39ul', 0, 'long.tv_aes_iviv');
        return $decrypted;
    }

    /**
     * 字符串的拼接
     *
     * @param $hex
     * @return string
     */
    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }

        return $string;
    }

}