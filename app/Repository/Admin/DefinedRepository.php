<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Defined;
use App\Repository\Searchable;

class DefinedRepository
{
    use Searchable;

    public static function add($data)
    {
        return Defined::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Defined::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Defined::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return Defined::query()->where($where)->first();
    }

    public static function delete($id)
    {
        return Defined::destroy($id);
    }

    public static function min($where)
    {
        return Defined::query()->where($where)->min('money');
    }
}
