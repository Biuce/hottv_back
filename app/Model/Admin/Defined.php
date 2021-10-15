<?php

namespace App\Model\Admin;

class Defined extends Model
{
    protected $table = "defined_assort_levels";
    protected $fillable = ['id', 'assort_id', 'money', 'created_at', 'updated_at'];

    public function assorts()
    {
        return $this->belongsTo('App\Model\Admin\Assort', 'assort_id', 'id');
    }
}
