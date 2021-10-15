<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Retail;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class RetailRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        $data = Retail::query()
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
        return Retail::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Retail::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Retail::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return Retail::query()->where($where)->first();
    }

    public static function findByList($where)
    {
        return Retail::query()->where($where)->get();
    }

    public static function delete($id)
    {
        return Retail::destroy($id);
    }

    public static function getMoneys($where)
    {
        return Retail::query()->where($where)->orderBy('money', 'ASC')->pluck('money');
    }
}
