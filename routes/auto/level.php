<?php

Route::get('/levels', 'LevelController@index')->name('level.index');
Route::get('/levels/create', 'LevelController@create')->name('level.create');
Route::post('/levels', 'LevelController@save')->name('level.save');
Route::get('/levels/{id}/edit', 'LevelController@edit')->name('level.edit');
Route::put('/levels/{id}', 'LevelController@update')->name('level.update');
Route::get('/levels/{id}/info', 'LevelController@info')->name('level.info');
Route::delete('/levels/{id}', 'LevelController@delete')->name('level.delete');