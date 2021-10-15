<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Model\Api\AuthCode;
use App\Repository\Admin\AuthCodeRepository;

class AuthCodeController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('code');
//    }

    /**
     * @Title: expire
     * @Description: 获取过期时间
     * @param Request $request
     * @return mixed
     * @Author: 李军伟
     */
    public function expire(Request $request)
    {
        $params = $request->input();
        $where = ['auth_code' => $params['auth_code']];
        $data = ['expire_at' => $params['expire'], 'status' => 1];
        AuthCodeRepository::updateByWhere($where, $data);

        return $this->setStatusCode(201)->success([]);
    }
}