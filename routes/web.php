<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('root');
Route::get('/contacts', 'HomeController@contacts')->name('contacts');
Route::get('/secret', 'HomeController@secret')->name('secret')->can('home.contact.secret');
Route::get('/posts/tags/{slug}', 'PostTagController@index')->name('posts.tags.index');

Route::resource('authors', 'AuthorController')->only(['show', 'edit', 'update']);
Route::resource('authors.comments', 'AuthorCommentController')->only(['store']);

Route::resource('posts', 'PostController');
Route::resource('posts.comments', 'PostCommentController')->only(['store']);
