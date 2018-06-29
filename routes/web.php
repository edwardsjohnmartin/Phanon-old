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
Route::get('/', 'PagesController@index')->name('index');
Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');
Route::get('/flow/{id}', 'PagesController@flow')->name('flow'); // course flow page.
Route::get('/sandbox', 'CodeController@sandbox')->name('sandbox');
Route::get('/code/current/', 'CodeController@current')->name('current');
Route::get('/code/{id}/{eid}', 'CodeController@exercise')->name('exercise');
Route::get('/code/review/{id}/{eid}', 'CodeController@review')->name('review');
Route::get('/solve/{id}', 'CodeController@project')->name('project');

Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
Route::resource('permissions', 'PermissionsController');
Route::resource('courses', 'CoursesController');
Route::resource('concepts', 'ConceptsController');
Route::resource('modules', 'ModulesController');
Route::resource('lessons', 'LessonsController');
Route::resource('exercises', 'ExercisesController');
Route::resource('projects', 'ProjectsController');
Route::resource('code', 'CodeController');
Route::resource('import', 'ImportController');
// cloning routes.
Route::get('/courses/{id}/clone', 'CoursesController@copy')->name('courses.clone');
Route::get('/modules/{id}/clone', 'ModulesController@copy')->name('modules.clone');
//Route::post('/modules', 'ModulesController@createClone');
Route::get('/lessons/{id}/clone', 'LessonsController@copy')->name('lessons.clone');
//Route::post('/lessons', 'LessonsController@createClone');
Route::get('/exercises/{id}/clone', 'ExercisesController@copy')->name('exercises.clone');
//Route::post('/exercises', 'ExercisesController@createClone');
Route::get('/projects/{id}/clone', 'ProjectsController@copy')->name('projects.clone');
//Route::post('/projects', 'ProjectsController@createClone');
Route::post('import/upload','ImportController@upload');

Route::put('exerciseprogress/complete/{exercise_id}', ['as' => 'exerciseprogress.complete', 'uses' => 'ExerciseProgressController@complete']);

Route::get('save', 'ExerciseProgressController@save')->name('save');

Route::get('current', 'CodeController@current')->name('current');

Route::get('newexercise/{exercise_id}', 'CodeController@newexercise')->name('newexercise');
Route::post('newexercises/save', 'ExerciseProgressController@newsave');