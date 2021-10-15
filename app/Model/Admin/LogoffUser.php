<?php

namespace App\Model\Admin;

class LogoffUser extends Model
{
    protected $fillable = ['id', 'user_id', 'parent_id', 'name', 'bank_name', 'bank_account', 'phone', 'status'];

    public function users()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id', 'id');
    }
}
