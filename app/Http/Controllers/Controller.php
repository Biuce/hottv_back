<?php

namespace App\Http\Controllers;

use App\Repository\Admin\MenuRepository;
use App\Repository\Admin\AdminUserRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Repository\APIHelper;
use App\Repository\Admin\EntityRepository;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $breadcrumb = [];
    protected $formNames = [];
    protected $ids = [];
    protected $idss = [];
    protected $lowers = [];
    protected $ids_list = [];
    protected $ids_list_all = [];
    protected $ids_list_all_tt = [];
    protected $agent = 3;

    public function __construct()
    {
        if (request()->ajax()) {
            return;
        }

        // 面包屑导航
        $this->breadcrumb[] = ['title' => '首页', 'url' => route('admin::index')];
        View::share('breadcrumb', $this->breadcrumb);

        // 菜单
        $route = request()->route();
        if (is_null($route)) {
            return;
        }
        $route = request()->route()->getName();
        // 获取当前的分组
        $group = MenuRepository::getGroup($route);
        View::share(['light_cur_route' => $route, 'light_cur_group' => $group]);
        if (is_null($currentRootMenu = MenuRepository::root($route))) {
            View::share('light_menu', []);
        } else {
            View::share('light_menu', $currentRootMenu);
            if ($route !== 'admin::aggregation' && $currentRootMenu['route'] === 'admin::aggregation') {
                View::share('autoMenu', EntityRepository::systemMenu());
            }
        }

        $this->formNames = array_merge($this->formNames, ['created_at']);
    }

    /**
     * @Title: send
     * @Description: 邮件发送
     * @param $name
     * @param $to
     * @param $subject
     * @Author: 李军伟
     */
    public function send($name, $to, $subject)
    {
        // Mail::send()的返回值为空，所以可以其他方法进行判断
        Mail::raw($name, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
        // 返回的一个错误数组，利用此可以判断是否发送成功
    }

    /**
     * @Title: getApiByBatch
     * @Description: 批量获取授权码（code码）
     * @param $data
     * @return string
     * @Author: 李军伟
     */
    public function getApiByBatch($data)
    {
        $type = 1;
        if (\Auth::guard('admin')->user()->type == 1) {
            $type = 2;
        }
        try {
            $apiStr = 'createcode';
            $api = new APIHelper();
            $body = [
                'num' => $data['number'],
                'valid_day' => $data['day'],
                'channel_id' => \Auth::guard('admin')->user()->channel_id,
                'enable_switch' => $type,
            ];
            $res = $api->post($body, $apiStr);
            $data = json_decode($res, true);
            return $data['data'];
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @Title: getApi
     * @Description: 获取第三方API接口数据(生成code)
     * @Author: 李军伟
     */
//    public function getApi(Request $request)
//    {
//        try {
//            $data = $request->only($this->formNames);
//            $apiStr = 'createcode';
//            $api = new APIHelper();
//            $body = [
//                'num' => $data['number'],
//                'valid_day' => $data['day'],
//                'channel_id' => \Auth::guard('admin')->user()->channel_id,
//            ];
//            $res = $api->post($body, $apiStr);
//            $data = json_decode($res, true);
//            return [
//                'code' => 0,
//                'msg' => trans('general.createSuccess'),
//                'redirect' => true,
//                'data' => $data['data']
//            ];
//        } catch (\Exception $e) {
//            return [
//                'code' => 1,
//                'msg' => $e->getMessage(),
//                'redirect' => false
//            ];
//        }
//    }

    //获取用户的所有下级ID
    public function get_downline($members, $mid, $level = 0)
    {
        $arr = array();
        foreach ($members as $key => $v) {
            if ($v['pid'] == $mid) {  //pid为0的是顶级分类
                $v['level'] = $level + 1;
                $arr[] = $v->id;
                $arr = array_merge($arr, $this->get_downline($members, $v['id'], $level + 1));
            }
        }
        return $arr;
    }

    // 通过用户id找到它的顶级父id
    public function getParentId($uid)
    {
        $where = ['id' => $uid];
        $info = AdminUserRepository::findByWhere($where);
        if ($info->pid > 1) {
            return $this->getParentId($info->pid);
        }

        return $info['id'];
    }

    // 通过国代id找到它的所有下级id
    public function getLowerByIds($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel != 2) {
                    $this->lowers[] = $info->id;
                    $this->getLowerByIds($info->id);
                }
            }
        }
    }

    // 通过国代id找到它的所有下级id
    public function getLowerId($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel != 0) {
                    $this->ids[] = $info->id;
                }
                $this->getLowerId($info->id);
            }
        }
    }

    // 通过国代id找到它的所有下级id
    public function getLowerIdss($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($info->is_cancel == 2) {
                    $this->idss[] = $info->id;
                }
                $this->getLowerIdss($info->id);
            }
        }
    }

    // 通过某个用户id找到它的所有下级id
    public function getLowerIds($uid)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                $this->ids_list[] = $info->id;
                $this->getLowerIds($info->id);
            }
        }
    }

    // 通过某个用户id找到它的所有下级id
    public function getLowerIdsByAll($uid, $level_id)
    {
        $where = ['pid' => $uid];
        $infos = AdminUserRepository::getListByWhere($where);
        if ($infos) {
            foreach ($infos as $info) {
                if ($level_id > 0 && $info->level_id == $level_id) {
                    $this->ids_list_all[] = $info->id;
                } else {
                    $this->ids_list_all_tt[] = $info->id;
                }

                $this->getLowerIdsByAll($info->id, $level_id);
            }
        }
    }

    public function isAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }
    }
}
