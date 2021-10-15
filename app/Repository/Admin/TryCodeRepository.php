<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\TryCode;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class TryCodeRepository
{
    use Searchable;

    public static function list($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        $data = TryCode::query()->where(function ($query) use ($condition) {
            Searchable::buildQuery($query, $condition);
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间
        return $data;
    }

    public static function add($data)
    {
        return TryCode::query()->create($data);
    }

    public static function find($id)
    {
        return TryCode::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return TryCode::query()->where($where)->first();
    }
}
