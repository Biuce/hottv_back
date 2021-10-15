<?php

namespace App\Model\Admin;

class TryCode extends Model
{
    protected $fillable = ['id', 'user_id', 'number', 'description'];

    public function users()
    {
        return $this->belongsTo('App\Model\Admin\AdminUser', 'user_id', 'id');
    }
}
