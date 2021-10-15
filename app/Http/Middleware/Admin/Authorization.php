<?php

namespace App\Http\Middleware\Admin;

use App\Model\Admin\Menu;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   string
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $user = Auth::guard($guard)->user();
        $route = Route::currentRouteName();
        $permission = Menu::where('route', $route)->first();
//        if (!in_array($user->id, config('light.superAdmin')) && (!$permission || !$user->can($permission->name))) {
//            if ($request->expectsJson()) {
//                return response()->json(['code' => 401, 'msg' => "未授权操作（路由别名：{$route}）"], 401);
//            }
//            abort(401, "未授权操作（路由别名：{$route}）");
//        }
        $array = [
            'admin::home.email',
            'admin::home.emailVerify',
            'admin::home.code',
            'admin::home.codeVerify',
            'admin::home.newPass',
            'admin::home.verify',
            'admin::home.done',
        ];
        if ($user->is_new == 0 && !in_array($request->route()->getName(), $array)) {
            return redirect(route('admin::home.email'));
        }
        return $next($request);
    }
}
