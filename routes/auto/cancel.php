<?php

Route::get('/cancels', 'CancelController@index')->name('cancel.index');
Route::get('/cancels/{id}/check', 'CancelController@check')->name('cancel.check');
Route::put('/cancels/{id}/{type}', 'CancelController@update')->name('cancel.update');
Route::post('/cancels/cancel', 'CancelController@cancel')->name('cancel.cancel');
Route::get('/cancels/{id}/look', 'CancelController@look')->name('cancel.look');
