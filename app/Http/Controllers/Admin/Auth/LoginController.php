<?php
/**
 * Date: 2019/2/25 Time: 10:37
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Model\Admin\AdminUser;
use App\Repository\Admin\AdminUserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EmailRequest;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\NewPasswordRequest;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $guard = 'admin';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:' . $this->guard)->except('logout');

        $this->username = $this->findUsername();
    }

    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'account';
        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * 基础功能-用户登录页面
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => trans('adminUser.captcha_empty'), // '图形验证码不能为空',
            'captcha.captcha' => trans('adminUser.captcha_error'), // '图形验证码错误',
        ], [
            $this->username() => trans('adminUser.account'), // '账号',
            'captcha' => trans('adminUser.captcha'), // '验证码',
        ]);
    }

    /**
     * @Title: login
     * @Description: 基础功能-用户登录
     * @param Request $request
     * @return array|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @Author: 李军伟
     */
    public function login(Request $request)
    {
        // 如果不是ajax方式，则非法请求
        $this->isAjax($request);
        $this->validateLogin($request);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // 检查用户是否已被注销
        $user = $this->guard()->getProvider()->retrieveByCredentials($this->credentials($request));
        if ($user && $user->is_cancel != 0) {
            return [
                'code' => 0,
                'msg' => trans('adminUser.user_cancel'),
                'redirect' => true,
                'is_cancel' => $user->is_cancel
            ];
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * 基础功能-退出登录
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('admin::login.show'));
    }

    public function guard()
    {
        return Auth::guard($this->guard);
    }

    public function username()
    {
//        return 'account';
        return $this->username;
    }

    protected function authenticated(Request $request, $user)
    {
        return [
            'code' => 0,
            'msg' => trans('general.login_success'),
            'redirect' => true,
            'is_new' => $user->is_new
        ];
    }

    public function passOne()
    {
        return view('admin.auth.passOne');
    }

    public function passEmail(Request $request)
    {
        $account = $request->input('name');
        if (empty($account)) {
            return [
                'code' => 1,
                'msg' => trans('adminUser.name_empty'),
                'redirect' => false
            ];
        }
        $info = $request->session()->get($account);
        $arr = explode('@', $info['email']);
        $rest = substr($arr[0], 0, -2);
        $arr[0] = str_replace($rest, str_repeat('*', strlen($rest)), $arr[0]);
        $email = $arr[0] . "@" . $arr[1];

        return view('admin.auth.passEmail', [
            'info' => $info,
            'email' => $email
        ]);
    }

    /**
     * @Title: passVerify
     * @Description: 邮箱验证
     * @param Request $request
     * @return array
     * @Author: 李军伟
     */
    public function passVerify(Request $request)
    {
        try {
            // 验证账号的合法性，如果合法则发送邮件到用户邮箱
            $account = $request->input('name');
            if (empty($account)) {
                throw new \Exception(trans('adminUser.name_empty'));
            }
            $where = ['account' => $account];
            $whereDue = ['email' => $account];
            $info = AdminUserRepository::findByWhereDue($where, $whereDue);
            if (!$info) {
                throw new \Exception(trans('general.account_not'));
            }
            if ($info->is_cancel != 0) {
                throw new \Exception(trans('adminUser.user_cancel'));
            }
            // 存储到session中
            $request->session()->put($info['account'], $info);

            // 发送邮件
            $code = getRandChar(6);
            // 把code放入到redis中，保存10分钟
            if (!Redis::get($info['email'])) {
                $to = $info['email'];
                $name = trans('general.email_tips1') . $code;
                $subject = trans('general.find_pass');
//                $send = $this->send($name, $to, $subject);
                $send = send_email($to, $subject, $name);
                if ($send['status'] == 1) {
                    Redis::setex($info['email'], 600, $code);
                }
            }

            if (empty($info)) {
                throw new \Exception(trans('general.account_not'));
            }
            return [
                'code' => 0,
                'msg' => trans('general.account_pass'),
                'redirect' => true,
                'data' => $info
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: emailVerify
     * @Description: code码验证
     * @param Request $request
     * @return array
     * @Author: 李军伟
     */
    public function emailVerify(Request $request)
    {
        try {
            $param = $request->input();
            if (empty($param['email'])) {
                throw new \Exception(trans('adminUser.email_empty'));
            }
            if (empty($param['code'])) {
                throw new \Exception(trans('general.enter_code'));
            }
            $code = Redis::get($param['email']);
            if (!$code || $code != strtoupper($param['code'])) {
                throw new \Exception(trans('general.code_past'));
            }
            return [
                'code' => 0,
                'msg' => trans('general.enter_right'),
                'redirect' => true,
                'data' => $request->session()->get($param['name'])
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    public function newPass(Request $request)
    {
        $account = $request->input('name');

        return view('admin.auth.newPass', ['info' => $request->session()->get($account)]);
    }

    /**
     * @Title: passEdit
     * @Description: 更改密码
     * @param NewPasswordRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function passEdit(NewPasswordRequest $request)
    {
        try {
            $param = $request->input();
            // 验证新密码和确认密码是否一致
            if ($param['password'] != $param['password_confirmation']) {
                return [
                    'code' => 1,
                    'msg' => trans('general.code_differ'),
                    'redirect' => false
                ];
            }
            $where = ['email' => $param['email']];
            $data['password'] = $param['password'];
            AdminUserRepository::updateByWhere($where, $data);
            return [
                'code' => 0,
                'msg' => trans('general.pass_success'),
                'redirect' => true
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: cancel
     * @Description: 注销页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function cancel()
    {
        return view('admin.auth.cancel');
    }

    public function check(Request $request)
    {
        try {
            $account = $request->input("name");
            if (empty($account)) {
                throw new \Exception(trans('adminUser.name_empty'));
            }
            // 验证账号是否存在
            $where = ['account' => $account];
            $info = AdminUserRepository::findByWhere($where);
            if (!$info) {
                throw new \Exception(trans('general.account_not'));
            }
            // 生成一个位于随机数
            $code = getRandChar(6);
            // 存储到session中
            $request->session()->put($account, $code);
            return [
                'code' => 0,
                'msg' => trans('general.pass_success'),
                'redirect' => true,
                'random' => $code
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }
}
