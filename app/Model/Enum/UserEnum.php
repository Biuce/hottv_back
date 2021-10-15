<?php
namespace App\Model\Enum;

class UserEnum
{
    // 状态类别
    const INVALID = -1; // 已删除
    const FREEZE = 0; // 冻结
    const NORMAL = 1; // 正常

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::INVALID:
                return '已删除';
            case self::NORMAL:
                return '正常';
            case self::FREEZE:
                return '冻结';
            default:
                return '正常';
        }
    }
}