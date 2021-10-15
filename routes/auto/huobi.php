<?php

Route::get('/huobis', 'HuobiController@index')->name('huobi.index');
Route::get('/huobis/create', 'HuobiController@create')->name('huobi.create');
Route::post('/huobis', 'HuobiController@save')->name('huobi.save');
Route::get('/huobis/{id}/edit', 'HuobiController@edit')->name('huobi.edit');
Route::put('/huobis/{id}', 'HuobiController@update')->name('huobi.update');
Route::get('/huobis/{id}/info', 'HuobiController@info')->name('huobi.info');
Route::delete('/huobis/{id}', 'HuobiController@delete')->name('huobi.delete');