<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoffUserRequest;
use App\Repository\Admin\LogoffUserRepository;
use App\Repository\Admin\AdminUserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CancelController extends Controller
{
    /**
     * @Title: index
     * @Description: 注销管理-注销列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index(Request $request)
    {
        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'type';
        $action = $request->get('action');
        $condition = $request->only($this->formNames);
        if (isset($condition['type']) && $condition['type'] == 1) {
            if (\Auth::guard('admin')->user()->id != 1) {
                $condition['status'] = ['=', 1];
                $condition['is_relation'] = ['=', 1];
            } else {
                $condition['is_relation'] = ['=', 2];
            }
            $type = 1;
        } else {
            if (\Auth::guard('admin')->user()->id != 1) {
                $condition['status'] = ['=', 0];
            } else {
                $condition['is_relation'] = ['=', 1];
            }
            $type = 0;
        }

        if (\Auth::guard('admin')->user()->id != 1) {
            $condition['parent_id'] = ['=', \Auth::guard('admin')->user()->id];
        } else {
            $condition['status'] = ['=', 1];
        }
        unset($condition['type']);
        $data = LogoffUserRepository::list($perPage, $condition);
        return view('admin.logoffUser.index', [
            'lists' => $data,  //列表数据
            'type' => $type,
        ]);
    }

    /**
     * @Title: check
     * @Description: 注销管理-编辑注销
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function check($id)
    {
        $info = LogoffUserRepository::find($id);
        return view('admin.logoffUser.check', [
            'id' => $id,
            'info' => $info
        ]);
    }

    /**
     * @Title: look
     * @Description: 注销管理-编辑注销
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function look($id)
    {
        $info = LogoffUserRepository::find($id);
        return view('admin.logoffUser.look', [
            'id' => $id,
            'info' => $info
        ]);
    }

    /**
     * @Title: update
     * @Description: 注销管理-更新注销 (国代审核)
     * @param Request $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(Request $request, $id, $type)
    {
        DB::beginTransaction(); //开启事务
        try {
            $info = LogoffUserRepository::find($id);
            if ($type == 1) {
                $param = ['status' => 1];
                LogoffUserRepository::update($id, $param);
                // 更新注销状态为已通过注销
                $user_param = ['is_cancel' => 1];
                AdminUserRepository::update($info['user_id'], $user_param);
            } else {
                // 更新注销状态为未注销
                $user_param = ['is_cancel' => 0];
                AdminUserRepository::update($info['user_id'], $user_param);
                // 删除注销表里面该用户数据
                LogoffUserRepository::delete($id);
            }
            DB::commit();  //提交
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            DB::rollback();  //回滚
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: cancel
     * @Description: 注销管理-更新注销 (管理员审核)
     * @param $id
     * @param $type
     * @return array
     * @Author: 李军伟
     */
    public function cancel(Request $request)
    {
        DB::beginTransaction(); //开启事务
        try {
            $data = $request->input();
            $info = LogoffUserRepository::find($data['id']);
            if ($data['type'] == 1) {
                $param = ['is_relation' => 2];
                LogoffUserRepository::update($data['id'], $param);
                // 更新注销状态为已通过注销
                $user_param = ['is_cancel' => 2, 'email' => order_no()];
                AdminUserRepository::update($info['user_id'], $user_param);
            } else {
                // 更新注销状态为未注销
                $user_param = ['is_cancel' => 1];
                AdminUserRepository::update($info['user_id'], $user_param);
                // 更新注销表里面该用户数据
                $param = ['status' => 0];
                LogoffUserRepository::update($data['id'], $param);
            }
            DB::commit();  //提交
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            DB::rollback();  //回滚
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }
}
