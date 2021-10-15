<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\AuthCode;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class AuthCodeRepository_1
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
        if (isset($condition['startTime']) && !empty($condition['startTime'])) {
            $start_time = $condition['startTime'] . " 00:00:00";
            $end_time = $condition['endTime'] . " 23:59:59";
            unset($condition['startTime']);
            unset($condition['endTime']);
            $data = AuthCode::query()
                ->where(function ($query) use ($condition) {
                    if (isset($condition['auth_code']) && $condition['auth_code'] != "") {
                        $query->where('auth_code', 'like', "%{$condition['auth_code']}%")->orWhere('remark', 'like', "%{$condition['auth_code']}%");
                    }
                })
                ->where(function ($query) use ($condition) {
                    unset($condition['auth_code']);
                    Searchable::buildQuery($query, $condition);
                })
                ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } else {
            $data = AuthCode::query()
                ->where(function ($query) use ($condition) {
                    if (isset($condition['auth_code']) && $condition['auth_code'] != "") {
                        $query->where('auth_code', 'like', "%{$condition['auth_code']}%")->orWhere('remark', 'like', "%{$condition['auth_code']}%");
                    }
                })
                ->where(function ($query) use ($condition) {
                    unset($condition['auth_code']);
                    Searchable::buildQuery($query, $condition);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        }
        return $data;
    }

    /**
     * @Title: listByWhere
     * @Description: 根据条件获取相应的数据
     * @param $condition
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     * @Author: 李军伟
     */
    public static function listByWhere($condition)
    {
        if (isset($condition['startTime']) && !empty($condition['startTime'])) {
            $start_time = $condition['startTime'] . " 00:00:00";
            $end_time = $condition['endTime'] . " 23:59:59";
            unset($condition['startTime']);
            unset($condition['endTime']);
            $data = AuthCode::query()
                ->where($condition)
                ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $data = AuthCode::query()
                ->where($condition)
                ->orderBy('id', 'desc')
                ->get();
        }

        return $data;
    }

    public static function add($data)
    {
        return AuthCode::query()->create($data);
    }

    public static function update($id, $data)
    {
        return AuthCode::query()->where('id', $id)->update($data);
    }

    public static function updateByWhere($where, $data)
    {
        return AuthCode::query()->where($where)->update($data);
    }

    public static function find($id)
    {
        return AuthCode::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return AuthCode::query()->where($where)->first();
    }

    public static function findByList($where)
    {
        return AuthCode::query()->where($where)->get();
    }

    public static function delete($id)
    {
        return AuthCode::destroy($id);
    }

    /**
     * @Title: incrementAuthCode
     * @Description: 定义自增字段
     * @param $where
     * @param $value
     * @return int
     * @Author: 李军伟
     */
    public static function incrementAuthCode($where, $value)
    {
        return AuthCode::query()->where($where)->increment($value);
    }

    /**
     * @Title: decrementAuthCode
     * @Description: 定义自减字段
     * @param $where
     * @param $value
     * @return int
     * @Author: 李军伟
     */
    public static function decrementAuthCode($where, $value)
    {
        return AuthCode::query()->where($where)->decrement($value);
    }

    // 获取授权码数量
    public static function lowerByCode($where, $month)
    {
        return AuthCode::query()->where($where)->whereMonth('created_at', $month)->count();
    }

    // 获取授权码总数量
    public static function countByCode($where)
    {
        return AuthCode::query()->where($where)->count();
    }

    // 管理员累计利润
    public static function addProfit()
    {
        return AuthCode::query()->sum('profit');
    }

    // 管理员的下级产生利润(按月份)
    public static function lowerByProfit($month)
    {
        return AuthCode::query()
            ->whereMonth('created_at', $month)
            ->sum('profit');
    }
}
