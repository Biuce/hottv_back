<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Huobi;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class HuobiRepository_1
{
    use Searchable;

    /**
     * @Title: list
     * @Description: 下级火币数据
     * @param $perPage
     * @param array $condition
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: 李军伟
     */
    public static function list($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        if (isset($condition['startTime']) && !empty($condition['startTime'])) {
            $start_time = $condition['startTime'] . " 00:00:00";
            $end_time = $condition['endTime'] . " 23:59:59";
            unset($condition['startTime']);
            unset($condition['endTime']);
            $data = Huobi::query()
                ->where(function ($query) use ($condition) {
                    Searchable::buildQuery($query, $condition);
                })
                ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } else {
            $data = Huobi::query()
                ->where(function ($query) use ($condition) {
                    Searchable::buildQuery($query, $condition);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        }
//        print_r(DB::getQueryLog());
        return $data;
    }

    public static function ownList($perPage, $condition = [], $where)
    {
        //        DB::connection()->enableQueryLog();#开启执行日志
        if ($condition['status'] == 4) {
            unset($condition['status']);
            // 获取管理员的直属下级
            $where_ids = ['pid' => 1];
            $ids = AdminUserRepository::getIds($where_ids);
            if (isset($condition['startTime']) && !empty($condition['startTime'])) {
                $start_time = $condition['startTime'] . " 00:00:00";
                $end_time = $condition['endTime'] . " 23:59:59";
                unset($condition['startTime']);
                unset($condition['endTime']);
                $data = Huobi::query()
                    ->where(function ($query) use ($condition) {
                        Searchable::buildQuery($query, $condition);
                    })
                    ->where($where)
                    ->whereIn("user_id", $ids)
                    ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                    ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                    ->orderBy('id', 'desc')
                    ->paginate($perPage);
            } else {
                $data = Huobi::query()
                    ->where(function ($query) use ($condition) {
                        Searchable::buildQuery($query, $condition);
                    })
                    ->where($where)
                    ->whereIn("user_id", $ids)
                    ->orderBy('id', 'desc')
                    ->paginate($perPage);
            }
        } else {
            unset($condition['status']);
            if (isset($condition['startTime']) && !empty($condition['startTime'])) {
                $start_time = $condition['startTime'] . " 00:00:00";
                $end_time = $condition['endTime'] . " 23:59:59";
                unset($condition['startTime']);
                unset($condition['endTime']);
                $data = Huobi::query()
                    ->where(function ($query) use ($condition) {
                        Searchable::buildQuery($query, $condition);
                    })
                    ->where($where)
                    ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                    ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                    ->orderBy('id', 'desc')
                    ->paginate($perPage);
            } else {
                $data = Huobi::query()
                    ->where(function ($query) use ($condition) {
                        Searchable::buildQuery($query, $condition);
                    })
                    ->where($where)
                    ->orderBy('id', 'desc')
                    ->paginate($perPage);
            }
        }
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间

        return $data;
    }

    public static function lists($perPage, $condition = [])
    {
        $data = Huobi::query()
            ->where($condition)
            ->where('money', '>', 0)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'fPage');

        return $data;
    }

    public static function listsByExport($condition = [], $month = "")
    {
        if (empty($month)) {
            $data = Huobi::query()
                ->where($condition)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $data = Huobi::query()
                ->where($condition)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->get();
        }

        return $data;
    }

    public static function lists_two($perPage, $condition = [])
    {
        $data = Huobi::query()
            ->where($condition)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'rPage');

        return $data;
    }

    public static function add($data)
    {
        return Huobi::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Huobi::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Huobi::query()->find($id);
    }

    public static function delete($id)
    {
        return Huobi::destroy($id);
    }

    // 充值、利润记录
    public static function record($where)
    {
        return Huobi::query()->where($where)->get();
    }

    // 当前代理人给上级带来的利润记录
    public static function levelByRecord($where)
    {
        $result = Huobi::query()->where($where)->get();
        $profile = 0;
        foreach ($result as $item) {
            $profile += $item->money;
        }

        return $profile;
    }

    // 当前代理人给上级带来的利润记录
    public static function levelByRecordByTime($where, $month)
    {
        $result = Huobi::query()->where($where)->whereMonth("created_at", $month)->get();
        $profile = 0;
        foreach ($result as $item) {
            $profile += $item->money;
        }

        return $profile;
    }

    /**
     * @Title: incrementContract
     * @Description: 定义自增字段
     * @param $where
     * @param $value
     * @return int
     * @Author: 李军伟
     */
    public static function incrementContract($where, $value)
    {
        return Huobi::query()->where($where)->increment($value);
    }

    /**
     * @Title: decrementContract
     * @Description: 定义自减字段
     * @param $where
     * @param $value
     * @return int
     * @Author: 李军伟
     */
    public static function decrementContract($where, $value)
    {
        return Huobi::query()->where($where)->decrement($value);
    }

    // 本月为下级充值
    public static function lowerByRecharge($where)
    {
        return Huobi::query()->where($where)->whereMonth('created_at', date('m'))->sum('money');
    }

    // 累计充值火币
    public static function lowerByAddRecharge($where)
    {
        return Huobi::query()->where($where)->sum('money');
    }

    // 累计下级产生利润（总计）
    public static function lowerByAddProfit($where)
    {
        return Huobi::query()->where($where)->sum('money');
    }

    // 下级产生利润(按月份)
    public static function lowerByProfit($month, $profit_where)
    {
        return Huobi::query()
            ->where($profit_where)
//            ->whereIn('user_id', $ids)
            ->whereMonth('created_at', $month)
            ->sum('money');
    }

    // 消耗火币(按月份)
    public static function expendByHuobi($where, $month)
    {
        return Huobi::query()->where($where)->whereMonth('created_at', $month)->sum('money');
    }

    // 下级生成授权码个数(按月份)
    public static function lowerByCode($month, $ids)
    {
        $data = 0;
        foreach ($ids as $id) {
            $where = ['user_id' => $id];
            $count = AuthCodeRepository::lowerByCode($where, $month);
            $data += $count;
        }

        return $data;
    }

    /**
     * @Title: getBalance
     * @Description: 根据条件获取相应金额
     * @param array $condition
     * @return mixed
     * @Author: 李军伟
     */
    public static function getBalance($where, $condition = [])
    {
        if (isset($condition['startTime']) && !empty($condition['startTime'])) {
            $start_time = $condition['startTime'] . " 00:00:00";
            $end_time = $condition['endTime'] . " 23:59:59";
            unset($condition['startTime']);
            unset($condition['endTime']);
            $data = Huobi::query()
                ->where($where)
                ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                ->orderBy('id', 'desc')
                ->sum('money');
        } else {
            $data = Huobi::query()
                ->where($where)
                ->orderBy('id', 'desc')
                ->sum('money');
        }

        return $data;
    }
}
