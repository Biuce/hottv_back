<?php
/**
 * Date: 2019/2/25 Time: 16:15
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository\Admin;

use App\Model\Admin\Equipment;
use App\Repository\Searchable;

class EquipmentRepository
{
    use Searchable;

    public static function listByGroupTest($where)
    {
        $lists = Equipment::query()->where($where)->get();
        $data = [];
        foreach ($lists as $key => $list) {
            if ($list->level_id == 3) {
                $data['3'][] = $list->money;
            } elseif ($list->level_id == 4) {
                $data['4'][] = $list->money;
            } elseif ($list->level_id == 5) {
                $data['5'][] = $list->money;
            } elseif ($list->level_id == 6) {
                $data['6'][] = $list->money;
            } elseif ($list->level_id == 7) {
                $data['7'][] = $list->money;
            } elseif ($list->level_id == 8) {
                $data['8'][] = $list->money;
            }
        }
        foreach ($data as &$item) {
            $item = bubbleSort($item);
        }
        return $data;
    }

    public static function list($where)
    {
        // 先判断该用户是否有设定
        $info = self::findByWhere($where);
        if ($info) {
            $list = Equipment::query()
                ->where($where)
                ->orderBy('level_id', 'ASC')
                ->get()
                ->groupBy("assort_id");
        } else {
            $list = Equipment::query()
                ->orderBy('level_id', 'ASC')
                ->get()
                ->groupBy("assort_id");
        }
        $data = [];
        foreach ($list as $items) {
            foreach ($items as $key => $item) {
                $data[$item->assorts->assort_name]['assort_id'] = $item->assort_id;
                $data[$item->assorts->assort_name]['levels'][] = $item->levels->level_name;
                $data[$item->assorts->assort_name]['money'][] = $item->money;
            }
        }
        $aaa = array_slice($data,0, -2);  // 30/60/180/365/
        $bbb = array_slice($data,4);  //  7/1/
        $ccc = array_slice($bbb,1);  //  1天
        $ddd = array_slice($bbb, 0, 1);  // 7天
        $in = array_merge($ddd, $aaa);
        $re = array_merge($ccc, $in);

        return $re;
    }

    public static function add($data)
    {
        return Equipment::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Equipment::query()->where('id', $id)->update($data);
    }

    public static function find($id)
    {
        return Equipment::query()->find($id);
    }

    public static function findByWhere($where)
    {
        return Equipment::query()->where($where)->first();
    }

    public static function delete($id)
    {
        return Equipment::destroy($id);
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
        return Equipment::query()->where($where)->increment($value);
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
        return Equipment::query()->where($where)->decrement($value);
    }

    public static function listByWhere($where)
    {
        $lists = Equipment::query()->where($where)->get();
        $data = [];
        foreach ($lists as $key => $list) {
            $data[$key]['user_id'] = \Auth::guard('admin')->user()->id;
            $data[$key]['assort_id'] = $list->assort_id;
            $data[$key]['level_id'] = $list->level_id;
            $data[$key]['money'] = $list->money;
            $data[$key]['created_at'] = date("Y-m-d H:i:s");
            $data[$key]['updated_at'] = date("Y-m-d H:i:s");
        }

        return $data;
    }

    public static function listByGroup($where)
    {
        $lists = Equipment::query()->where($where)->get();
        $data = [];
        foreach ($lists as $key => $list) {
            if ($list->level_id == 3) {
                $data['3'][] = $list->money;
            } elseif ($list->level_id == 4) {
                $data['4'][] = $list->money;
            } elseif ($list->level_id == 5) {
                $data['5'][] = $list->money;
            } elseif ($list->level_id == 6) {
                $data['6'][] = $list->money;
            } elseif ($list->level_id == 7) {
                $data['7'][] = $list->money;
            } elseif ($list->level_id == 8) {
                $data['8'][] = $list->money;
            }
        }
        foreach ($data as &$item) {
            $item = bubbleSort($item);
        }
        return $data;
    }

    public static function listForUserByWhere($where, $data)
    {
        $lists = Equipment::query()->where($where)->orderBy('level_id', 'ASC')->get();
        $lists = $lists->toArray();
        if (\Auth::guard('admin')->user()->level_id == 3) {
            unset($lists[0]);
        }
        $lists = array_values($lists);
        foreach ($lists as $key => $list) {
            $id = $list['id'];
            $param = ['money' => $data[$key]];

            self::update($id, $param);
        }
    }
}
