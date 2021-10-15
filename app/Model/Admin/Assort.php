<?php

namespace App\Model\Admin;

class Assort extends Model
{
    protected $fillable = ['id', 'assort_name', 'duration'];
    protected $table = 'en_assorts';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $customer_lang_name = session('customer_lang_name');
        if (!empty($customer_lang_name) && array_key_exists($customer_lang_name, config('app.locales'))) {
            if ($customer_lang_name != 'zh') {
                $this->table = $customer_lang_name . "_assorts";
            } else {
                $this->table = "assorts";
            }
        }
    }
}
