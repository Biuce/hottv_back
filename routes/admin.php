<?php
/**
 * Date: 2019/2/25 Time: 9:31
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

use Illuminate\Support\Str;

Route::get('lang/{locale}', ['as'=>'lang.change', 'uses'=>'LanguageController@setLocale']);

Route::group(
    [
        'as' => 'admin::'
    ],
    function () {
        Route::middleware('log')->group(function () {
            Route::get('/login', 'Auth\LoginController@showLogin')->name('login.show');
            Route::post('/login', 'Auth\LoginController@login')->name('login');
            Route::post('/login/check', 'Auth\LoginController@check')->name('login.check');
            Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

            Route::get('/login/passOne', 'Auth\LoginController@passOne')->name('login.passOne');
            Route::post('/login/passVerify', 'Auth\LoginController@passVerify')->name('login.passVerify');
            Route::get('/login/passEmail', 'Auth\LoginController@passEmail')->name('login.passEmail');
            Route::post('/login/emailVerify', 'Auth\LoginController@emailVerify')->name('login.emailVerify');
            Route::get('/login/newPass', 'Auth\LoginController@newPass')->name('login.newPass');
            Route::post('/login/passEdit', 'Auth\LoginController@passEdit')->name('login.passEdit');

            Route::get('/login/cancel', 'Auth\LoginController@cancel')->name('login.cancel');
        });

        Route::middleware('log:admin', 'auth:admin', 'authorization:admin')->group(function () {
//        Route::middleware('log:admin', 'auth:admin')->group(function () {
            Route::get('/index', 'HomeController@showIndex')->name('index');
            Route::get('/home/email', 'HomeController@email')->name('home.email');
            Route::post('/home/emailVerify', 'HomeController@emailVerify')->name('home.emailVerify');
            Route::get('/home/code', 'HomeController@code')->name('home.code');
            Route::post('/home/codeVerify', 'HomeController@codeVerify')->name('home.codeVerify');
            Route::get('/home/newPass', 'HomeController@newPass')->name('home.newPass');
            Route::post('/home/verify', 'HomeController@verify')->name('home.verify');
            Route::get('/home/done', 'HomeController@done')->name('home.done');
            Route::get('/aggregation', 'HomeController@showAggregation')->name('aggregation');

            //NEditor路由
            Route::post('/neditor/serve/{type}', 'NEditorController@serve')->name('neditor.serve');

            Route::match(['get', 'post'], '/ueditor/serve', 'UEditorController@serve')->name('ueditor.serve');

            // 管理员用户管理
            Route::get('/admin_users', 'AdminUserController@index')->name('adminUser.index');
            Route::get('/admin_users/all', 'AdminUserController@all')->name('adminUser.all');
            Route::get('/admin_users/logoff', 'AdminUserController@logoff')->name('adminUser.logoff');
            Route::get('/admin_users/list', 'AdminUserController@list')->name('adminUser.list');
            Route::get('/admin_users/create', 'AdminUserController@create')->name('adminUser.create');
            Route::post('/admin_users', 'AdminUserController@save')->name('adminUser.save');
            Route::get('/admin_users/{id}/edit', 'AdminUserController@edit')->name('adminUser.edit');
            Route::put('/admin_users/{id}', 'AdminUserController@update')->name('adminUser.update');
            Route::delete('/admin_users/{id}', 'AdminUserController@delete')->name('adminUser.delete');
            Route::post('/admin_users/info', 'AdminUserController@info')->name('adminUser.info');
            Route::get('/admin_users/{id}/check', 'AdminUserController@check')->name('adminUser.check');
            Route::get('/admin_users/{id}/examine', 'AdminUserController@examine')->name('adminUser.examine');
            Route::get('/admin_users/{id}/look', 'AdminUserController@look')->name('adminUser.look');
            Route::get('/admin_users/{id}/lower', 'AdminUserController@lower')->name('adminUser.lower');
            Route::get('/admin_users/{id}/recharge', 'AdminUserController@recharge')->name('adminUser.recharge');
            Route::get('/admin_users/{id}/visual', 'AdminUserController@visual')->name('adminUser.visual');
            Route::get('/admin_users/{id}/stepOne', 'AdminUserController@stepOne')->name('adminUser.stepOne');
            Route::get('/admin_users/{id}/stepTwo', 'AdminUserController@stepTwo')->name('adminUser.stepTwo');
            Route::post('/admin_users/pay', 'AdminUserController@pay')->name('adminUser.pay');
            Route::get('/admin_users/userInfo', 'AdminUserController@userInfo')->name('adminUser.userInfo');
            Route::get('/admin_users/userEdit', 'AdminUserController@userEdit')->name('adminUser.userEdit');
            Route::put('/admin_users/userUpdate', 'AdminUserController@userUpdate')->name('adminUser.userUpdate');
            Route::get('/admin_users/changePwd', 'AdminUserController@changePwd')->name('adminUser.changePwd');
            Route::put('/admin_users/savePwd', 'AdminUserController@savePwd')->name('adminUser.savePwd');
            Route::get('/admin_users/cancel', 'AdminUserController@cancel')->name('adminUser.cancel');
            Route::get('/admin_users/code', 'AdminUserController@code')->name('adminUser.code');
            Route::post('/admin_users/checkEmail', 'AdminUserController@checkEmail')->name('adminUser.checkEmail');
            Route::get('/admin_users/reCancel', 'AdminUserController@reCancel')->name('adminUser.reCancel');
            Route::post('/admin_users/saveCancel', 'AdminUserController@saveCancel')->name('adminUser.saveCancel');
            Route::post('/admin_users/material', 'AdminUserController@material')->name('adminUser.material');
            Route::get('/admin_users/{id}/level', 'AdminUserController@level')->name('adminUser.level');
            Route::put('/admin_users/{id}/remark', 'AdminUserController@remark')->name('adminUser.remark');
            Route::get('/admin_users/{id}/cost', 'AdminUserController@cost')->name('adminUser.cost');
            Route::post('/admin_users/adjust', 'AdminUserController@adjust')->name('adminUser.adjust');
            Route::get('/admin_users/detail', 'AdminUserController@detail')->name('adminUser.detail');
            Route::put('/admin_users/{id}/change', 'AdminUserController@change')->name('adminUser.change');
            Route::post('/admin_users/export', 'AdminUserController@export')->name('adminUser.export');
            Route::get('/admin_users/{id}/role', 'AdminUserController@role')->name('adminUser.role.edit');
            Route::put('/admin_user/{id}/role', 'AdminUserController@updateRole')->name('adminUser.role.update');

            // 菜单管理
            Route::get('/menus', 'MenuController@index')->name('menu.index');
            Route::get('/menus/list', 'MenuController@list')->name('menu.list');
            Route::get('/menus/create', 'MenuController@create')->name('menu.create');
            Route::post('/menus', 'MenuController@save')->name('menu.save');
            Route::get('/menus/{id}/edit', 'MenuController@edit')->name('menu.edit');
            Route::put('/menus/{id}', 'MenuController@update')->name('menu.update');
            Route::delete('/menus/{id}', 'MenuController@delete')->name('menu.delete');
            Route::post('/menus/discovery', 'MenuController@discovery')->name('menu.discovery');
            Route::post('/menus/batch', 'MenuController@batch')->name('menu.batch');

            // 角色管理
            Route::get('/roles', 'RoleController@index')->name('role.index');
            Route::get('/roles/list', 'RoleController@list')->name('role.list');
            Route::get('/roles/create', 'RoleController@create')->name('role.create');
            Route::post('/roles', 'RoleController@save')->name('role.save');
            Route::get('/roles/{id}/edit', 'RoleController@edit')->name('role.edit');
            Route::put('/roles/{id}', 'RoleController@update')->name('role.update');
            Route::delete('/roles/{id}', 'RoleController@delete')->name('role.delete');

            Route::get('/roles/{id}/permission', 'RoleController@permission')->name('role.permission.edit');
            Route::put('/roles/{id}/permission', 'RoleController@updatePermission')->name('role.permission.update');

            // 配置管理
            Route::get('/configs', 'ConfigController@index')->name('config.index');
            Route::get('/configs/list', 'ConfigController@list')->name('config.list');
            Route::get('/configs/create', 'ConfigController@create')->name('config.create');
            Route::post('/configs', 'ConfigController@save')->name('config.save');
            Route::get('/configs/{id}/edit', 'ConfigController@edit')->name('config.edit');
            Route::put('/configs/{id}', 'ConfigController@update')->name('config.update');
            Route::delete('/configs/{id}', 'ConfigController@delete')->name('config.delete');

            // 日志
            Route::get('/logs', 'LogController@index')->name('log.index');
            Route::get('/logs/list', 'LogController@list')->name('log.list');
            Route::get('/logs/create', 'LogController@create')->name('log.create');

            Route::post('/material', 'OrderController@material')->name('order.material');
            Route::post('/contract', 'ContractController@contract')->name('contract.contract');
            // 自动加载生成的其它路由
            foreach (new DirectoryIterator(base_path('routes/auto')) as $f) {
                if ($f->isDot()) {
                    continue;
                }
                $name = $f->getPathname();
                if ($f->isFile() && Str::endsWith($name, '.php')) {
                    require $name;
                }
            }
        });
    }
);
