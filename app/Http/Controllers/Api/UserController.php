<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\Request;
use App\Model\Api\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 返回用户列表
     * @return mixed
     */
    public function index()
    {
        $users = User::paginate(3);
        $data = UserResource::collection($users);
        return $this->setStatusCode(201)->success($data);
    }

    /**
     * 返回单一用户信息
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        $data = new UserResource($user);
        return $this->setStatusCode(201)->success($data);
    }

    /**
     * 用户注册
     * @param UserRequest $request
     * @return string
     *
     */
    public function store(UserRequest $request)
    {
        User::create($request->all());
        return $this->setStatusCode(201)->success([]);
    }

    /**
     * 用户登录
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        $token = Auth::guard('api')->attempt(['name' => $request->name, 'password' => $request->password]);
        if ($token) {
            return $this->setStatusCode(201)->success(['token' => 'bearer ' . $token]);
        }
        return $this->failed(trans('interface.account_fail'), 400);
    }

    /**
     * 用户退出
     * @return mixed
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->success('退出成功...');
    }

    /**
     * 返回当前登录用户信息
     * @return mixed
     */
    public function info()
    {
        $user = Auth::guard('api')->user();
        return $this->success(new UserResource($user));
    }
}
