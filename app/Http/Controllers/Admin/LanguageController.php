<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/2/9
 * Time: 17:29
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Repository\Admin\AdminUserRepository;

class LanguageController extends Controller
{
    public function setLocale($lang)
    {
        if (array_key_exists($lang, config('app.locales'))) {
            session(['applocale' => $lang]);
            session(['customer_lang_name' => $lang, 'customer_id' => \Auth::guard('admin')->user()->id]);
            // 把当前语言保存到用户表里面
            $data = ['language' => $lang];
            AdminUserRepository::update(\Auth::guard('admin')->user()->id, $data);
        }
        return back()->withInput();
    }
}
