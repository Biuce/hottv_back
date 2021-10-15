<?php

Route::get('/channels', 'ChannelController@index')->name('channel.index');
Route::get('/channels/create', 'ChannelController@create')->name('channel.create');
Route::post('/channels', 'ChannelController@save')->name('channel.save');
Route::get('/channels/{id}/edit', 'ChannelController@edit')->name('channel.edit');
Route::put('/channels/{id}', 'ChannelController@update')->name('channel.update');
Route::get('/channels/{id}/info', 'ChannelController@info')->name('channel.info');
