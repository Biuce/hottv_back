<?php

Route::get('/equipments', 'EquipmentController@index')->name('equipment.index');
Route::get('/equipments/create', 'EquipmentController@create')->name('equipment.create');
Route::post('/equipments', 'EquipmentController@save')->name('equipment.save');
Route::post('/equipments/edit', 'EquipmentController@edit')->name('equipment.edit');
