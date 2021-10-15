<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Assort;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class AssortRepository_1
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        $data = Assort::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间
        return $data;
    }

    public static function add($data)
    {
        return Assort::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Assort::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Assort::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return Assort::query()->where($where)->first();
    }

    public static function findByList($where)
    {
        return Assort::query()->where($where)->get();
    }

    public static function findByWhereLike($name)
    {
        return Assort::query()->where('assort_name', 'like', '%' . $name . '%')->first();
    }

    public static function delete($id)
    {
        return Assort::destroy($id);
    }

    public static function getValue()
    {
        return Assort::query()->pluck('assort_name');
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
        return Assort::query()->where($where)->increment($value);
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
        return Assort::query()->where($where)->decrement($value);
    }
}
