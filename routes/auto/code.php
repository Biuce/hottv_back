<?php

Route::get('/codes', 'AuthCodeController@index')->name('code.index');
Route::get('/codes/create', 'AuthCodeController@create')->name('code.create');
Route::get('/codes/getApi', 'AuthCodeController@getApi')->name('code.getApi');
Route::post('/codes', 'AuthCodeController@save')->name('code.save');
Route::get('/codes/{id}/edit', 'AuthCodeController@edit')->name('code.edit');
Route::put('/codes/{id}', 'AuthCodeController@update')->name('code.update');
Route::put('/codes/remark', 'AuthCodeController@remark')->name('code.remark');
Route::get('/codes/{id}/info', 'AuthCodeController@info')->name('code.info');
Route::delete('/codes/{id}', 'AuthCodeController@delete')->name('code.delete');
Route::get('/codes/export', 'AuthCodeController@export')->name('code.export');
