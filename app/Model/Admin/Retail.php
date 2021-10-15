<?php

namespace App\Model\Admin;

class Retail extends Model
{
    protected $table = "defined_retail";
    protected $fillable = ['id', 'user_id', 'assort_id', 'money', 'created_at', 'updated_at'];

    public function assorts()
    {
        return $this->belongsTo('App\Model\Admin\Assort', 'assort_id', 'id');
    }
}
