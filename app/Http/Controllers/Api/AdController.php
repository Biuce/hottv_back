<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repository\Admin\AuthCodeRepository;

class AdController extends Controller
{
    public function advertising(Request $request)
    {
        $params = $request->input();
        $where = ['auth_code' => $params['auth_code']];
        $data = ['expire_at' => $params['expire'], 'status' => 1];
        AuthCodeRepository::updateByWhere($where, $data);

        return $this->setStatusCode(201)->success([]);
    }
}