<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use App\Repository\Admin\AdminUserRepository;

class LangLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $locale = getConfig('LOCAL');
        $locale = session('customer_lang_name');
        if (isset($locale) AND array_key_exists($locale, Config::get('app.locales'))) {
            App::setLocale($locale);
        } else {
            // This is optional as Laravel will automatically set the fallback language if there is none specified
            // 如果session没有获取到语言，则判断该用户里面是否有设置语言，如果有则获取该用户语言，否则获取默认语言
            if (!isset(\Auth::guard('admin')->user()->id)) {
                // 如果用户未登陆，则获取默认语言
                App::setLocale(Config::get('app.fallback_locale'));
            } else {
                $info = AdminUserRepository::find(\Auth::guard('admin')->user()->id);
                if (isset($info) && $info->language != "") {
                    App::setLocale($info->language);
                    session(['customer_lang_name' => $info->language, 'customer_id' => $info->id]);
                } else {
                    App::setLocale(Config::get('app.fallback_locale'));
                }
            }
        }
        return $next($request);
    }
}
