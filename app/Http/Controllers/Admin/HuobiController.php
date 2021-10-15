<?php
/**
 * Date: 2019/2/25 Time: 14:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use PHPExcel;
use PHPExcel_IOFactory;
use Maatwebsite\Excel\Facades\Excel;
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
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    /**
     * @Title: export
     * @Description: 导入到excel
     * @return void
     * @Author: 李军伟
     * @throws \PHPExcel_Exception
     */
    public function export(Request $request)
    {
        $this->formNames[] = 'date2';
        $condition = $request->only($this->formNames);
        $title = trans('huobi.huobi_recode') . "-";
        $objExcel = new PHPExcel();
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
        $objActSheet = $objExcel->getActiveSheet(0);
        $objActSheet->setTitle($title); //设置excel的标题
        $objActSheet->setCellValue('A1', trans('huobi.id'));
        $objActSheet->setCellValue('B1', trans('general.create'));
        $objActSheet->setCellValue('C1', trans('huobi.event'));
        $objActSheet->setCellValue('D1', trans('huobi.money'));
        $baseRow = 2; //数据从N-1行开始往下输出 这里是避免头信息被覆盖
        // 根据条件获取授权码列表
        if (isset($condition['date2']) && !empty($condition['date2'])) {
            $times = explode(" - ", $condition['date2']);
            $condition['startTime'] = $times[0];
            $condition['endTime'] = $times[1];
        }
        if (isset($condition['status']) && $condition['status'] == 1) {  // 充入火币
            // 自己充入火币记录
            if (\Auth::guard('admin')->user()->id == 1) {
                $own_where = ['status' => 1, 'type' => 1];
            } else {
                $own_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 1];
            }
            $data = HuobiRepository::listByWhere($own_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 2) { // 为下级充值
            if (\Auth::guard('admin')->user()->id == 1) {
                $xiaji_where = ['status' => 1, 'type' => 2];
            } else {
                $xiaji_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 1, 'type' => 2];
            }
            $data = HuobiRepository::listByWhere($xiaji_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 3) { // 生成授权码
            if (\Auth::guard('admin')->user()->id == 1) {
                $own_code_where = ['status' => 0, 'type' => 2];
            } else {
                $own_code_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 2];
            }
            $data = HuobiRepository::listByWhere($own_code_where, $condition);
        } elseif (isset($condition['status']) && $condition['status'] == 4) { // 下级产生的利润
            if (\Auth::guard('admin')->user()->id == 1) {
                $xiaji_code_where = ['status' => 0, 'type' => 1];
            } else {
                $xiaji_code_where = ['user_id' => \Auth::guard('admin')->user()->id, 'status' => 0, 'type' => 1];
            }
            $data = HuobiRepository::listByWhere($xiaji_code_where, $condition);
        } else {
            unset($condition['status']);
            // 获取该用户及该用户的下级数据（获取所有用户id）
            if (\Auth::guard('admin')->user()->id != 1) {
                $condition['money'] = ['>', 0];
                $condition['user_id'] = ['=', \Auth::guard('admin')->user()->id];
            }
            $data = HuobiRepository::defaultList($condition);
        }
        foreach ($data as $key => $value) {
            $i = $baseRow + $key;
            $objExcel->getActiveSheet()->setCellValue('A' . $i, $value['id']);
            $objExcel->getActiveSheet()->setCellValue('B' . $i, $value['created_at']);
            $details = \App\Repository\Admin\AdminUserRepository::find($value['own_id']);
            $assort = \App\Repository\Admin\AssortRepository::find($value['assort_id']);
            if ($value['status'] == 1 && $value['type'] == 2) {
                if (isset($details->name)) {
                    $event = trans('adminUser.by') . $details->name . trans('adminUser.lower');
                } else {
                    $event = trans('adminUser.lower');
                }
            } elseif ($value['status'] == 1 && $value['type'] == 1) {
                $event = trans('adminUser.myself');
            } elseif ($value['status'] == 0 && $value['type'] == 1) {
                if ($details->account == $value['user_account']) {
                    $event = $details->name . trans('general.generate') . $assort->assort_name;
                } else {
                    $event = $details->name . trans('general.as_lower') . $value['user_account'] . trans('general.generate') . $assort->assort_name;
                }
            } elseif ($value['status'] == 0 && $value['type'] == 2) {
                if (isset($details->name)) {
                    $event = $details->name . trans('general.generate') . $assort->assort_name;
                } else {
                    $event = trans('general.generate') . $assort->assort_name;
                }
            } else {
                $event = "";
            }
            $objExcel->getActiveSheet()->setCellValue('C' . $i, $event);

            if ($value['type'] == 2) {
                $money = '-' . number_format($value['money'], 2);
            } else {
                $money = '+' . number_format($value['money'], 2);
            }
            $objExcel->getActiveSheet()->setCellValue('D' . $i, $money);
        }
        // 执行导出
        $time = date('Y-m-d-') . rand_code(4);
        $objExcel->setActiveSheetIndex(0);
        //4、输出
        $objExcel->setActiveSheetIndex();
        header('Content-Type: applicationnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $title . $time . ".xls");
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
}
