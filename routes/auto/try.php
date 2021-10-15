<?php

Route::get('/tries', 'AuthCodeController@list')->name('try.list');
Route::get('/tries/add', 'AuthCodeController@add')->name('try.add');
Route::post('/tries', 'AuthCodeController@hold')->name('try.hold');
Route::get('/tries/records', 'AuthCodeController@records')->name('try.records');
Route::get('/tries/export', 'AuthCodeController@tryExport')->name('try.tryExport');
Route::post('/tries/export', 'AuthCodeController@tryExport')->name('try.tryExport');
