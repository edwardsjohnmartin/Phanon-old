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

Route::get('/', 'PagesController@index');

//TODO: Possible get rid of the index route and change all instances of it to just be '/'
//      I don't know what the advantages/disadvantages of it would be but it would be one less route on this page
Route::get('/index', 'PagesController@index');
Route::get('/dashboard', 'PagesController@dashboard');
Route::get('/sandbox', 'PagesController@sandbox');

Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
Route::resource('permissions', 'PermissionsController');

Route::resource('courses', 'CoursesController');
Route::resource('modules', 'ModulesController');
Route::resource('lessons', 'LessonsController');
Route::resource('exercises', 'ExercisesController');
Route::resource('projects', 'ProjectsController');

Route::get('/courses/{id}/fullview', 'CoursesController@fullview');
Route::get('/courses/{id}/clone', 'CoursesController@clone');

Route::get('/modules/{id}/clone', 'ModulesController@clone');
Route::post('/modules', 'ModulesController@create_clone');

Route::get('/lessons/{id}/clone', 'LessonsController@clone');
Route::post('/lessons', 'LessonsController@create_clone');

Route::get('/exercises/{id}/clone', 'ExercisesController@clone');
Route::post('/exercises', 'ExercisesController@create_clone');

Route::get('/projects/{id}/clone', 'ProjectsController@clone');
Route::post('/projects', 'ProjectsController@create_clone');