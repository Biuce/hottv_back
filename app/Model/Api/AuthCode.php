<?php

namespace App\Model\Api;

class AuthCode extends Model
{
    protected $fillable = ['id', 'assort_id', 'user_id', 'auth_code', 'remark', 'status', 'expire_at'];

    public function assorts()
    {
        return $this->belongsTo('App\Model\Admin\Assort', 'assort_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id', 'id');
    }
}
