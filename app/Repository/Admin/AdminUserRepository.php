<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\AdminUser;
use App\Model\Admin\Menu;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class AdminUserRepository
{
    use Searchable;

    public static function list($perPage, $ids, $param = [], $keyword = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        // 当前用户为国代，则获取下级注销用户，否则不获取
        if (\Auth::guard('admin')->user()->level_id == 3) {
            // 获取该用户下级注销的用户
//            $wh_id ='is_cancel';->select('*',DB::raw("$wh_id as wh_id"))
            $first_query = AdminUser::query()
                ->whereIn('id', $ids)
                ->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('account', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                });

            $data = AdminUser::query()
                ->where(function ($query) use ($param) {
                    Searchable::buildQuery($query, $param);
                })->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('account', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                })
                ->union($first_query)
                ->orderBy('level_id', 'ASC')
                ->paginate($perPage);
        } else {
            $data = AdminUser::query()
                ->where(function ($query) use ($param) {
                    Searchable::buildQuery($query, $param);
                })->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('account', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                })
                ->orderBy('level_id', 'ASC')
                ->paginate($perPage);
        }
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间

        return $data;
    }

    /**
     * @Title: listByView
     * @Description: 第三视角查看代理人列表
     * @param $perPage
     * @param $id
     * @param $ids
     * @param array $param
     * @param array $keyword
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: 李军伟
     */
    public static function listByView($perPage, $id, $ids, $param = [], $keyword = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        // 当前用户为国代，则获取下级注销用户，否则不获取
        if ($id == 3) {
            // 获取该用户下级注销的用户
//            $wh_id ='is_cancel';->select('*',DB::raw("$wh_id as wh_id"))
            $first_query = AdminUser::query()
                ->whereIn('pid', $ids)
                ->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('account', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                });

            $data = AdminUser::query()
                ->where(function ($query) use ($param) {
                    Searchable::buildQuery($query, $param);
                })->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('account', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                })
                ->union($first_query)
                ->orderBy('level_id', 'ASC')
                ->paginate($perPage);
        } else {
            $data = AdminUser::query()
                ->where(function ($query) use ($param) {
                    Searchable::buildQuery($query, $param);
                })->where(function ($query) use ($keyword) {
                    if (isset($keyword['name']) && $keyword['name'] != "") {
                        $query->where('name', 'like', "%{$keyword['name']}%")
                            ->orWhere('remark', 'like', "%{$keyword['name']}%");
                    }
                })
                ->orderBy('level_id', 'ASC')
                ->paginate($perPage);
        }
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间

        return $data;
    }

    /**
     * @Title: listByCancel
     * @Description: 所有用户
     * @param $perPage
     * @param array $condition
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: 李军伟
     */
    public static function listByCancel($perPage, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        $data = AdminUser::query()
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间

        return $data;
    }

    public static function logoff($perPage, $ids)
    {
        // 获取注销用户的直属下级
        $data = AdminUser::query()->whereIn('pid', $ids)->orderBy('id', 'desc')->paginate($perPage);

        return $data;
    }

    // public static function add($data)
    // {
    //     $data['password'] = bcrypt($data['password']);
    //     return AdminUser::query()->create($data);
    // }

    public static function addByPass($data)
    {
        unset($data['agency']);
        unset($data['own']);
        unset($data['choice']);
        unset($data['assort']);
        unset($data['daysOneList']);
        unset($data['daysSevenList']);
        unset($data['daysThirtyList']);
        unset($data['daysNinetyList']);
        unset($data['daysEightyList']);
        unset($data['yearsList']);
        unset($data['retailList']);
        unset($data['barriersList']);

        return AdminUser::query()->create($data);
    }

    public static function update($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return AdminUser::query()->where('id', $id)->update($data);
    }

    public static function updateByWhere($where, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return AdminUser::query()->where($where)->update($data);
    }

    public static function find($id)
    {
        return AdminUser::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return AdminUser::query()->where($where)->first();
    }

    public static function findByWhereDue($where, $whereDue)
    {
        return AdminUser::query()->where($where)->orWhere($whereDue)->first();
//        DB::connection()->enableQueryLog();#开启执行日志
//        $aaa = AdminUser::query()->where(function($query) use ($where) {
//            $query->where($where);
//        })->orWhere(function($query) use ($whereDue) {
//            $query->where($whereDue);
//        })->first();
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间
//        return $aaa;
    }

    public static function roles(AdminUser $user)
    {
        return $user->roles();
    }

    public static function setDefaultPermission(AdminUser $user)
    {
        $logoutPermission = Menu::query()->where('route', 'admin::logout')->first();
        if ($logoutPermission) {
            $user->givePermissionTo($logoutPermission->name);
        }
    }

    public static function delete($id)
    {
        return AdminUser::destroy($id);
    }

    public static function decr($where, $balance)
    {
        return AdminUser::query()->where($where)->decrement("balance", $balance);
    }

    public static function decrByTry($where, $try)
    {
        return AdminUser::query()->where($where)->decrement("try_num", $try);
    }

    public static function incrByDecr($where, $balance, $try_name)
    {
        $results = DB::table('admin_users')->where($where)
            ->update(array(
                'balance' => DB::raw('balance - ' . $balance),
                'try_num' => DB::raw('try_num + ' . $try_name),
            ));
        return $results;
    }

    public static function incr($where, $balance)
    {
        $results = DB::table('admin_users')->where($where)
            ->update(array(
                'balance' => DB::raw('balance + ' . $balance),
                'profit' => DB::raw('profit + ' . $balance),
            ));
        return $results;
    }

    public static function incrByTry($where, $try_num)
    {
        $results = DB::table('admin_users')->where($where)
            ->update(array(
                'try_num' => DB::raw('try_num + ' . $try_num),
            ));
        return $results;
    }

    public static function personIncr($where)
    {
        $results = DB::table('admin_users')->where($where)
            ->update(array(
                'person_num' => DB::raw('person_num + 1'),
            ));
        return $results;
    }

    public static function getIdsByCount($where)
    {
        return AdminUser::query()->where($where)->count();
    }

    public static function getIdsByWhere($where)
    {
        return AdminUser::query()->where($where)->pluck('id')->toArray();
    }

    public static function getDataByWhere($where)
    {
        return AdminUser::query()->select(['id', 'pid', 'level_id'])->where($where)->get();
    }

    public static function getIds($where)
    {
        return AdminUser::query()->where($where)->pluck('id');
    }

    public static function getListByWhere($where)
    {
        return AdminUser::query()->where($where)->get();
    }

    /**
     * @Title: getList
     * @Description: 名下代理人
     * @param $perPage
     * @param $ids
     * @param array $param
     * @param array $condition
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: 李军伟
     */
    public static function getList($perPage, $ids, $condition = [])
    {
//        DB::connection()->enableQueryLog();#开启执行日志
        // 当前用户为国代，则获取下级注销用户，否则不获取
        if (\Auth::guard('admin')->user()->level_id == 3) {
            if (isset($condition['startTime']) && !empty($condition['startTime'])) {
                $start_time = $condition['startTime'] . " 00:00:00";
                $end_time = $condition['endTime'] . " 23:59:59";
                $data = AdminUser::query()
                    ->whereIn('id', $ids)
                    ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                    ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                    ->orderBy('level_id', 'ASC')
                    ->paginate($perPage);
            } else {
                $data = AdminUser::query()
                    ->whereIn('id', $ids)
                    ->orderBy('level_id', 'ASC')
                    ->paginate($perPage);
            }
        } else {
            if (isset($condition['startTime']) && !empty($condition['startTime'])) {
                $start_time = $condition['startTime'] . " 00:00:00";
                $end_time = $condition['endTime'] . " 23:59:59";
                $data = AdminUser::query()
                    ->whereIn('id', $ids)
                    ->whereRaw('created_at >= ' . "'" . $start_time . "'")
                    ->whereRaw('created_at <= ' . "'" . $end_time . "'")
                    ->orderBy('level_id', 'ASC')
                    ->paginate($perPage);
            } else {
                $data = AdminUser::query()
                    ->whereIn('id', $ids)
                    ->orderBy('level_id', 'ASC')
                    ->paginate($perPage);
            }
        }
//        print_r(DB::getQueryLog());   //获取查询语句、参数和执行时间

        return $data;
    }

    public static function getGroup($ids)
    {
        $data = AdminUser::query()
            ->whereIn('id', $ids)
            ->orderBy('level_id', 'ASC')
            ->get()
            ->groupBy('level_id');

        return $data;
    }
}
