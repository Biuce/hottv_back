<?php

Route::get('/assorts', 'AssortController@index')->name('assort.index');
Route::get('/assorts/create', 'AssortController@create')->name('assort.create');
Route::post('/assorts', 'AssortController@save')->name('assort.save');
Route::get('/assorts/{id}/edit', 'AssortController@edit')->name('assort.edit');
Route::put('/assorts/{id}', 'AssortController@update')->name('assort.update');
Route::get('/assorts/{id}/info', 'AssortController@info')->name('assort.info');
Route::delete('/assorts/{id}', 'AssortController@delete')->name('assort.delete');