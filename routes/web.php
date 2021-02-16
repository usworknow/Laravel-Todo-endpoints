<?php
Route::get('/', function () {
	return view('welcome');
});

Route::get('get-default-message', 'DefaultController@getDefaultMessage');