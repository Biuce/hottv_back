<?php

namespace App\Model\Admin;

class Huobi extends Model
{
    protected $fillable = ['id', 'user_id', 'event', 'money', 'status', 'created_at', 'updated_at'];

    public function levels()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id', 'id');
    }

    public function assorts()
    {
        return $this->belongsTo('App\Model\Admin\Assort', 'assort_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id', 'id');
    }
}
