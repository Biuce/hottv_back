<?php
/**
 * Date: 2019/2/25 Time: 14:35
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Model\Admin\Equipment;
use App\Model\Admin\Defined;
use App\Http\Controllers\Controller;
use App\Repository\Admin\DefinedRepository;
use App\Repository\Admin\EquipmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Admin\NewPasswordRequest;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\HuobiRepository;
use App\Repository\Admin\AuthCodeRepository;

class HomeController extends Controller
{
    public $count = 0;

    public function email()
    {
        return view('admin.home.email');
    }

    public function emailVerify(Request $request)
    {
        try {
            $email = $request->input('email');
            // 验证邮箱格式的正确性
            if (empty($email)) {
                throw new \Exception(trans('home.email_not'));
            }
            $regex = '/^\S+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/';
            $result = preg_match($regex, $email);
            if ($result != 1) {
                throw new \Exception(trans('home.email_not_format'));
            }
            // 验证该邮箱是否已经被使用
            $email_where = ['email' => $email];
            $info = AdminUserRepository::findByWhere($email_where);
            if ($info) {
                throw new \Exception(trans('home.email_exist'));
            }
            // 存储到session中
            $request->session()->put(\Auth::guard('admin')->user()->account, $email);

            // 发送邮件
            $code = getRandChar(6);
            // 把code放入到redis中，保存10分钟
            if (!Redis::get($email)) {
                $to = $email;
                $name = trans('general.pass_tips') . $code;
                $subject = trans('general.set_password');
//                $send = $this->send($name, $to, $subject);
                $send = send_email($to, $subject, $name);
                // 如果send为null，则说明发送成功，进行缓存10分钟
                if ($send['status'] == 1) {
                    Redis::setex($email, 600, $code);
                }
            }

            return [
                'code' => 0,
                'msg' => trans('home.sub_success'),
                'redirect' => true,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    public function code(Request $request)
    {
        $email = $request->session()->get(\Auth::guard('admin')->user()->account);

        return view('admin.home.code', ['email' => $email]);
    }

    /**
     * @Title: codeVerify
     * @Description: 验证code码是否正确
     * @param Request $request
     * @return array
     * @Author: 李军伟
     */
    public function codeVerify(Request $request)
    {
        try {
            $code = $request->input('code');
            $email = $request->session()->get(\Auth::guard('admin')->user()->account);
            $code_1 = Redis::get($email);
            if (!$code_1 || strtoupper($code) != $code_1) {
                throw new \Exception(trans('home.code_exp'));
            }

            return [
                'code' => 0,
                'msg' => trans('home.sub_success'),
                'redirect' => true,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    public function newPass()
    {
        return view('admin.home.newPass');
    }

    /**
     * @Title: verify
     * @Description: 更改密码
     * @param NewPasswordRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function verify(NewPasswordRequest $request)
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
            $email = $request->session()->get(\Auth::guard('admin')->user()->account);
            $where = ['account' => \Auth::guard('admin')->user()->account];
            $data['email'] = $email;
            $data['password'] = $param['password'];
            $data['is_new'] = 1;
            AdminUserRepository::updateByWhere($where, $data);
            return [
                'code' => 0,
                'msg' => trans('general.pass_success'),
                'redirect' => true,
                'data' => $param
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    public function done()
    {
        return view('admin.home.done');
    }

    /**
     * 基础功能-首页
     */
    public function showIndex()
    {
        return view('admin.home.index');
    }

    /**
     * 内容管理-内容管理
     */
    public function showAggregation()
    {
        $level_id = \Auth::guard('admin')->user()->level_id;
        $parent_id = $this->getParentId(\Auth::guard('admin')->user()->id);
        // 如果级别为自定义，则从自定义里面获取数据
        if ($level_id == 8) {
            $where = ['user_id' => \Auth::guard('admin')->user()->id];
            $equipment = Defined::query()->where($where)->orderBy('assort_id')->get();
        } else {
            // 先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
            $guodai_where = ['user_id' => $parent_id];
            $res = EquipmentRepository::findByWhere($guodai_where);
            if ($res) {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => $parent_id];
                $equipment = Equipment::query()->where($where)->get();
            } else {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => 1];
                $equipment = Equipment::query()->where($where)->get();
            }
        }
        $month = date('m');
        $date = date("Y-m", time());
        $last_month = "0" . (date("m") - 1);
        $last = strtotime("-1 month", time());
        $last_date = date("Y-m", $last);

        if (\Auth::guard('admin')->user()->id == 1) {
            $where = ['is_try' => 1];
        } else {
            $where = ['is_try' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
        }
        // 本月总计生成授权码数量
        $count_code = AuthCodeRepository::countByCode($where);
        // 本月本人生成授权码
        $month_code = AuthCodeRepository::lowerByCode($where, dates($date));
        // 上月本人生成授权码
        $last_month_code = AuthCodeRepository::lowerByCode($where, dates($last_date));
        // 现获取所有的下级id
        $all_users = AdminUserRepository::getDataByWhere([]);
        $ids = $this->get_downline($all_users, \Auth::guard('admin')->user()->id, \Auth::guard('admin')->user()->level_id);
//        $user_where = ['pid' => \Auth::guard('admin')->user()->id];
//        $ids = AdminUserRepository::getIdsByWhere($user_where);
        $profit_where = ['status' => 0, 'type' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
        // 总计下级产生利润
        $sum_profit = HuobiRepository::lowerByAddProfit($profit_where);
        // 本月下级产生利润
        $month_profit = HuobiRepository::lowerByProfit(dates($date), $profit_where);
        // 上月下级产生利润
        $last_month_profit = HuobiRepository::lowerByProfit(dates($last_date), $profit_where);
        // 上月消耗火币
        $expend_where = ['type' => 2, 'user_id' => \Auth::guard('admin')->user()->id];
        $month_expend = HuobiRepository::expendByHuobi($expend_where, dates($last_date));
        // 获取总共的会员数
        $this->getLevel(\Auth::guard('admin')->user()->id);
        // 本月下级生成授权码个数
        $lower_month_code = HuobiRepository::lowerByCode(dates($date), $ids);
        // 上月下级生成授权码个数
        $lower_last_month_code = HuobiRepository::lowerByCode(dates($last_date), $ids);
        $locale = session('customer_lang_name');
        // 当前登录用户是否存在下级
//        $type = 0;
//        if (\Auth::guard('admin')->user()->level_id > 7) {
//            // 验证该用户是不是最下级
//            $own_money = Defined::query()->where(['user_id' => \Auth::guard('admin')->user()->id])->pluck('money');
//            $i = 0;
//            foreach ($own_money as $k => $v) {
//                if (($this->list[$k] - $v) < 2) {
//                    $i++;
//                }
//            }
//            if ($i == 4) {
//                $type = 1;
//            }
//        }
//        $data = $equipment->toArray();
//        $arrDemo = arraySequence($data,'money', 'SORT_ASC');
        return view('admin.home.content', [
            'equipment' => $equipment,
            'month_code' => $month_code,
            'last_month_code' => $last_month_code,
            'count_code' => $count_code,
            'sum_profit' => $sum_profit,
            'month_profit' => $month_profit,
            'last_month_profit' => $last_month_profit,
            'month_expend' => $month_expend,
            'user_count' => $this->count,
            'lower_month_code' => $lower_month_code,
            'lower_last_month_code' => $lower_last_month_code,
            'locale' => $locale ? $locale : "en",
//            'type' => $type,
        ]);
    }

    // 获取下级的个数
    public function getLevel($id)
    {
        $count_where = ['pid' => $id];
        $ids = AdminUserRepository::getIdsByWhere($count_where);
        foreach ($ids as $info) {
            if (!empty($info)) {
                $this->count++;
                $this->getLevel($info);
            }
        }
    }
}
