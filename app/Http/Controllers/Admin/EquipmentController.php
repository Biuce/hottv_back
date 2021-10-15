<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EquipmentRequest;
use App\Repository\Admin\EquipmentRepository;
use App\Repository\Admin\CostRepository;
use App\Repository\Admin\AssortRepository;
use App\Repository\Admin\LevelRepository;
use App\Repository\Admin\DefinedRepository;
use App\Model\Admin\Level;
use App\Model\Admin\Equipment;
use App\Model\Admin\Assort;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repository\Admin\RetailRepository;

class EquipmentController extends Controller
{
    protected $formNames = ['id', 'money', 'level_id', 'assort_id', 'user_id'];

    /**
     * @Title: index
     * @Description: 级别分配管理-级别分配列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index()
    {
        $user_id = \Auth::guard('admin')->user()->id;
        $parent_id = $this->getParentId($user_id);
        // 先查看一下表里面是否有该用户的配置
//        $where = ['user_id' => $parent_id];
//        $info = EquipmentRepository::findByWhere($where);
//        if (!$info) {
//            $where = ['user_id' => 1];
//        }
//        $data = EquipmentRepository::listByGroupTest($where);
//        // 获取当前国代的零售价
//        $cost = $this->getRetail($parent_id);
//
//        if (empty($data))
//            return '';
        // 获取配置列表
        $assort = Assort::query()->orderBy('duration', 'ASC')->pluck('assort_name');
        $list_where = ['user_id' => $parent_id];
        $lists = EquipmentRepository::listByGroup($list_where);
        // 获取零售价
        $retail = RetailRepository::getMoneys($list_where);
        // 入门门槛
        $cost = CostRepository::getMoneys($list_where);
        return view('admin.equipment.index', [
            'data' => $lists,
            'lists' => $assort,
            'retail' => $retail,
            'cost' => $cost,
        ]);
//        return view('admin.equipment.index', [
//            'lists' => $data,  //列表数据
//            'prices' => $cost,
//        ]);
    }

    /**
     * @Title: create
     * @Description: 级别分配管理-新增级别分配
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create()
    {
        // 获取级别信息
        $level = Level::query()->select(['id', 'level_name'])->get();
        // 获取配套信息
        $assort = Assort::query()->select(['id', 'assort_name'])->get();

        return view('admin.equipment.add', [
            'level' => $level,
            'assort' => $assort
        ]);
    }

    /**
     * @Title: save
     * @Description: 级别分配管理-保存级别分配
     * @param EquipmentRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function save(EquipmentRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            EquipmentRepository::add($data);
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

    public function edit(Request $request)
    {
        DB::beginTransaction(); //开启事务
        try {
            // 如果不是ajax方式，则非法请求
            $this->isAjax($request);
            $data = $request->input('param');
            $params = $data;
            $locale = session('customer_lang_name') ? session('customer_lang_name') : "en";
            if ($locale == "zh" || $locale == "kr") {
                unset($data[0]);
                unset($data[1]);
                if (\Auth::guard('admin')->user()->level_id == 3) {
                    unset($data[2]);
                }
                unset($data[8]);
            } elseif ($locale == "en") {
                unset($data[0]);
                unset($data[1]);
                unset($data[2]);
                unset($data[3]);
                if (\Auth::guard('admin')->user()->level_id == 3) {
                    unset($data[4]);
                }
                unset($data[10]);
            } elseif ($locale == "my") {
                unset($data[0]);
                unset($data[1]);
                unset($data[2]);
                unset($data[3]);
                unset($data[4]);
                if (\Auth::guard('admin')->user()->level_id == 3) {
                    unset($data[5]);
                }
                unset($data[11]);
            }
//            elseif ($locale == "kr") {
//                unset($data[0]);
//                unset($data[1]);
//                if (\Auth::guard('admin')->user()->level_id == 3) {
//                    unset($data[2]);
//                }
//                unset($data[9]);
//            }
            $data = array_values($data);
            // 根据配套名称获取配套id
            if ($params[0] == "Kod") {
                $name = $params[2];
            } else {
                $name = $params[0];
            }
            $assort = AssortRepository::findByWhereLike($name);
            if ($assort->id < 5) {
                foreach ($data as $kk => $item) {
                    if (!is_numeric($data[$kk])) {
                        return [
                            'code' => 1,
                            'msg' => trans('equipment.tips'),
                            'redirect' => true
                        ];
                    }
                    // 下一位数字不能比上一个数字小
                    if (isset($data[$kk + 1]) && round($data[$kk + 1], 2) <= round($data[$kk], 2)) {
                        return [
                            'code' => 1,
                            'msg' => trans('equipment.tips'),
                            'redirect' => true
                        ];
                    }
                }
            } else {
                foreach ($data as $kk => $item) {
                    if (!is_numeric($data[$kk])) {
                        return [
                            'code' => 1,
                            'msg' => trans('equipment.tips'),
                            'redirect' => true
                        ];
                    }
                    // 下一位数字不能比上一个数字小
                    if (isset($data[$kk + 1]) && round($data[$kk + 1], 2) < round($data[$kk], 2)) {
                        return [
                            'code' => 1,
                            'msg' => trans('equipment.tips'),
                            'redirect' => true
                        ];
                    }
                }
            }
            // 1、判断$data最后一位（自定义）的值，如果该值大于自定义用户的金额则不能提交
            $parent_id = $this->getParentId(\Auth::guard('admin')->user()->id);
            $where_parent = ['generation_id' => $parent_id, 'assort_id' => $assort->id];
            // 如果是国代，则要判断更改金额是否比自己的金额低
            if (\Auth::guard('admin')->user()->level_id == 3) {
                $defined_where = ['user_id' => $parent_id, 'level_id' => 3, 'assort_id' => $assort->id];
                $selfMoney = EquipmentRepository::findByWhere($defined_where);
                if ($assort->id < 5) {
                    if ($data[0] <= $selfMoney->money) {
                        return [
                            'code' => 1,
                            'msg' => trans('equipment.tips'),
                            'redirect' => true
                        ];
                    }
                }
//                else {
//                    dd("1111");
//                    if ($data[0] < $selfMoney->money) {
//                        return [
//                            'code' => 1,
//                            'msg' => trans('equipment.tips'),
//                            'redirect' => true
//                        ];
//                    }
//                }
            }

            $min = DefinedRepository::min($where_parent);
            // 获取当前国代的零售价
            $cost = $this->getRetail($parent_id);
            // bccomp(end($data), $min, 2)结果为1，代表$data > $min 结果为0代表$data = $min 结果为-1代表$data < $min
            if (isset($min) && !empty($min) && bccomp(end($data), $min, 2) == 1) {
                return [
                    'code' => 1,
                    'msg' => trans('equipment.define'),  // 自定义金额大于已生成自定义用户金额
                    'redirect' => true
                ];
            }
            // 2、判断$data最后一位（自定义）的值，验证自定义金额是否大于或者等于零售价
            if ($assort->duration == 1) {
                $jiage = $cost[0];
            } elseif ($assort->duration == 7) {
                $jiage = $cost[1];
            } elseif ($assort->duration == 30) {
                $jiage = $cost[2];
            } elseif ($assort->duration == 90) {
                $jiage = $cost[3];
            } elseif ($assort->duration == 180) {
                $jiage = $cost[4];
            } elseif ($assort->duration == 365) {
                $jiage = $cost[5];
            }
            if ($assort->id < 5) {
                if (bccomp(end($data), $jiage, 2) == 1 || bccomp(end($data), $jiage, 2) == 0) {
                    return [
                        'code' => 1,
                        'msg' => trans('equipment.define1'),  // 自定义金额必须小于零售价
                        'redirect' => true
                    ];
                }
            } else {
                if (bccomp(end($data), $jiage, 2) == 1) {
                    return [
                        'code' => 1,
                        'msg' => trans('equipment.define1'),  // 自定义金额必须小于零售价
                        'redirect' => true
                    ];
                }
            }

            // 验证在assrot_levels表中是否有这个国代的配置,如果有则更新相应记录，如果没有则整体添加然后再更新
            $where = ['user_id' => \Auth::guard('admin')->user()->id];
            $info = EquipmentRepository::findByWhere($where);
            // 已经存在，则只更新相应的数据
            if ($info) {
                $user_where = ['assort_id' => $assort->id, 'user_id' => \Auth::guard('admin')->user()->id];
                EquipmentRepository::listForUserByWhere($user_where, $data);
            } else {
                // 先整体插入数据
                $list_where = ['user_id' => 1];
                $lists = EquipmentRepository::listByWhere($list_where);
                Equipment::query()->insert($lists);
                // 然后再更新相对应的数据
                $user_where = ['assort_id' => $assort->id, 'user_id' => \Auth::guard('admin')->user()->id];
                EquipmentRepository::listForUserByWhere($user_where, $data);
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
     * @Title: getRetail
     * @Description: 获取当前国代的零售成本
     * @param $parent_id
     * @return array
     * @Author: 李军伟
     */
    public function getRetail($parent_id)
    {
        $retail_where = ['user_id' => $parent_id];
        $retailList = RetailRepository::getMoneys($retail_where);

        return $retailList->toArray();
    }
}
