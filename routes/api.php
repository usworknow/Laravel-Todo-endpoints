<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'AuthController@login')->name('auth.login');
Route::post('register', 'AuthController@register')->name('auth.register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout','AuthController@logout')->name('auth.logout');
    Route::get('get-todos', 'TodoListController@getTodoItems')->name('todo.get');
    Route::post('filter-todos', 'TodoListController@getTodoItemsBySearch')->name('todo.get');
    Route::post('create-todo','TodoListController@createTodoItem')->name('todo.create');
    Route::post('update-todo','TodoListController@updateTodoItem')->name('todo.update');
    Route::get('delete-todo/{id}','TodoListController@removeTodoItem')->name('todo.delete');
});