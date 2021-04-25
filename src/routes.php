<?php

Route::get('/api/media/find', '\Frontkom\NovaMediaLibrary\Controllers\MediaController@findFiles');
Route::post('/api/media/upload', '\Frontkom\NovaMediaLibrary\Controllers\MediaController@uploadFile');
Route::post('/api/media/update', '\Frontkom\NovaMediaLibrary\Controllers\MediaController@updateFile');
Route::get('/api/media', '\Frontkom\NovaMediaLibrary\Controllers\MediaController@getFiles');

