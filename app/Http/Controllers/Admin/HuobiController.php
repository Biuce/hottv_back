<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HuobiRequest;
use App\Repository\Admin\HuobiRepository;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\AuthCodeRepository;
use Illuminate\Database\QueryException;
use App\Repository\Admin\EquipmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HuobiController extends Controller
{
    protected $formNames = ['id', 'user_id', 'event', 'money', 'status'];

    /**
     * @Title: index
     * @Description: 火币管理-火币列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index(Request $request)
    {
        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'date2';
        $condition = $request->only($this->formNames);
        if (isset($condition['date2']) && !empty($condition['date2'])) {
            $times = explode(" - ", $condition['date2']);
            $condition['startTime'] = $times[0];
            $condition['endTime'] = $times[1];
        }
        $params = $condition;
        unset($condition['date2']);
        if (isset($condition['status']) && $condition['status'] == 1) {  // 充入火币
            // 自己充入火币记录
            if (\Auth::guard('admin')->user()->id == 1) {
                $own_where = ['status' => 1, 'type' => 1];
            } else {
                $own_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 1];
            }
            $total = $this->getTotal($own_where, $condition);
            $data = HuobiRepository::ownList($perPage, $condition, $own_where);
        } elseif (isset($condition['status']) && $condition['status'] == 2) { // 为下级充值
            if (\Auth::guard('admin')->user()->id == 1) {
                $xiaji_where = ['status' => 1, 'type' => 2];
            } else {
                $xiaji_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 2];
            }
            $total = $this->getTotal($xiaji_where, $condition);
            $data = HuobiRepository::ownList($perPage, $condition, $xiaji_where);
        } elseif (isset($condition['status']) && $condition['status'] == 3) { // 生成授权码
            if (\Auth::guard('admin')->user()->id == 1) {
                $own_code_where = ['status' => 0, 'type' => 2];
            } else {
                $own_code_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 2];
            }
            $total = $this->getTotal($own_code_where, $condition);
            $data = HuobiRepository::ownList($perPage, $condition, $own_code_where);
        } elseif (isset($condition['status']) && $condition['status'] == 4) { // 下级产生的利润
            if (\Auth::guard('admin')->user()->id == 1) {
                $xiaji_code_where = ['status' => 0, 'type' => 1];
            } else {
                $xiaji_code_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 1];
            }
            $total = $this->getTotal($xiaji_code_where, $condition);
            $data = HuobiRepository::ownList($perPage, $condition, $xiaji_code_where);
        } else {
            unset($condition['status']);
            // 获取该用户及该用户的下级数据（获取所有用户id）
            if (\Auth::guard('admin')->user()->id != 1) {
                $condition['money'] = ['>', 0];
                $condition['user_id'] = ['=', \Auth::guard('admin')->user()->id];
            }
            $total = $this->getTotal($condition, []);
            $data = HuobiRepository::list($perPage, $condition);
        }

        // 本月为下级充值
        $lower_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 2];
        $lower_recharge = HuobiRepository::lowerByRecharge($lower_where);
        // 累计下级产生利润
        if (\Auth::guard('admin')->user()->id == 1) {
            $add_profit = AuthCodeRepository::addProfit();
        } else {
            $where = ['status' => 0, 'type' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
            $add_profit = HuobiRepository::lowerByAddProfit($where);
        }
        $locale = getConfig('LOCAL');
        $data->date2 = $request->date2;
        $data->status = $request->status;

        return view('admin.huobi.index', [
            'lists' => $data,  //列表数据
            'lower_recharge' => $lower_recharge,
            'add_profit' => $add_profit,
            'condition' => $params,
            'locale' => $locale,
            'total' => $total,
        ]);
    }

    // 统计总数
    public function getTotal($condition, $params)
    {
        $total = 0;
        if (\Auth::guard('admin')->user()->id != 1) {  // 不是超级用户
            if (!isset($condition['type'])) {
                if (!empty($params)) {
                    unset($params['status']);
                    $condition = array_merge($condition, $params);
                }
                unset($condition['money']);
                if (isset($condition['user_id']) && is_array($condition['user_id'])) {
                    $condition['user_id'] = $condition['user_id'][1];
                }
                $condition['type'] = 1;
                $addAmount = HuobiRepository::addAmount($condition);
                $condition['type'] = 2;
                $reduction = HuobiRepository::addAmount($condition);
                $total = $addAmount - $reduction;
            } else {
                if (!empty($params)) {
                    unset($params['status']);
                    $condition = array_merge($condition, $params);
                }
                $addAmount = HuobiRepository::addAmount($condition);
                $total = $addAmount;
            }
        }

        return $total;
    }

    /**
     * @Title: create
     * @Description: 火币管理-新增火币
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create()
    {
        return view('admin.huobi.add');
    }

    /**
     * @Title: save
     * @Description: 火币管理-保存火币
     * @param HuobiRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function save(HuobiRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            HuobiRepository::add($data);
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: edit
     * @Description: 火币管理-编辑火币
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function edit($id)
    {
        $info = HuobiRepository::find($id);
        return view('admin.huobi.add', [
            'id' => $id,
            'info' => $info
        ]);
    }

    /**
     * @Title: update
     * @Description: 火币管理-更新火币
     * @param HuobiRequest $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(HuobiRequest $request, $id)
    {
        $data = $request->only($this->formNames);

        try {
            HuobiRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: info
     * @Description: 火币详情
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function info($id)
    {
        $level = HuobiRepository::find($id);

        return view('admin.huobi.info', [
            'id' => $id,
            'level' => $level,
        ]);
    }

    /**
     * @Title: delete
     * @Description: 火币管理-删除火币
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function delete($id)
    {
        try {
            HuobiRepository::delete($id);
            return [
                'code' => 0,
                'msg' => trans('general.deleteSuccess'),
                'redirect' => true
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

}
