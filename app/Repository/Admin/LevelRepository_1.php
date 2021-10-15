<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Level;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class LevelRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        $data = Level::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间
        return $data;
    }

    public static function findByWhere($where)
    {
        return Level::query()->where($where)->first();
    }

    public static function findByList($where)
    {
        return Level::query()->where($where)->get();
    }

    public static function add($data)
    {
        return Level::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Level::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Level::query()->find($id);
    }

    public static function delete($id)
    {
        return Level::destroy($id);
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
        return Level::query()->where($where)->increment($value);
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
        return Level::query()->where($where)->decrement($value);
    }
}
