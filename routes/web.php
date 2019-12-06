<?php

Route::get('/tags/{tag}', 'TagsController@index');
Route::post('/posts/{post}/comments', 'CommentsController@toPost');
Route::post('/informations/{information}/comments', 'CommentsController@toInformation');

Route::resource('/posts', 'PostsController');
Route::get('/', 'PostsController@index');

Route::view('/contacts', 'contacts');
Route::view('/about', 'about');

Route::post('/feedbacks', 'FeedbacksController@store');

Route::view('/admin', 'admin')->middleware('auth', 'can:administrate');

Route::get('/admin/feedbacks', 'FeedbacksController@list');

Route::resource('/admin/posts', 'AdminPostsController');

Route::resource('/admin/informations', 'AdminInformationsController');
Route::get('/informations', 'InformationsController@index');
Route::get('/informations/{information}', 'InformationsController@show');

Auth::routes();
