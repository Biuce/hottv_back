<?php

namespace App\Model\Admin;

class Level extends Model
{
    protected $fillable = ['id', 'level_name'];

    protected $table = 'en_levels';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $customer_lang_name = session('customer_lang_name');
        if (!empty($customer_lang_name) && array_key_exists($customer_lang_name, config('app.locales'))) {
            if ($customer_lang_name != 'zh') {
                $this->table = $customer_lang_name . "_levels";
            } else {
                $this->table = "levels";
            }
        }
    }
}
