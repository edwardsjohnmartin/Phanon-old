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

//Route::get('/code/current/', 'CodeController@current')->name('current');
//Route::get('/code/{id}/{eid}', 'CodeController@exercise')->name('exercise');
//Route::get('/code/review/{id}/{eid}', 'CodeController@review')->name('review');
//Route::get('/solve/{id}', 'CodeController@project')->name('project');

Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
Route::resource('permissions', 'PermissionsController');
Route::resource('courses', 'CoursesController');
Route::resource('concepts', 'ConceptsController');
Route::resource('modules', 'ModulesController');
Route::resource('lessons', 'LessonsController');
Route::resource('exercises', 'ExercisesController');
Route::resource('projects', 'ProjectsController');

Route::get('/import/index', 'ImportController@index')->name('import.index');
Route::post('/import/upload','ImportController@upload')->name('import.upload');

//Route::get('/current', 'CodeController@current')->name('current');

Route::get('/code/exercise/{exercise_id?}', 'CodeController@exercise')->name('exercise.code');
Route::post('/code/exercise/save', 'ExerciseProgressController@save')->name('exercise.save');

Route::get('/code/project/{project_id?}', 'CodeController@project')->name('project.code');
Route::post('/code/project/save', 'ProjectProgressController@save')->name('project.save');

Route::get('/projects/{id}/partners', 'ProjectsController@partners')->name('project.partners');
Route::get('/courses/{id}/teams', 'CoursesController@teams')->name('course.teams');
Route::post('/teams', 'TeamsController@createTeam')->name('teams.create');
Route::post('/assignRandomTeams', 'TeamsController@assignRandomTeams')->name('teams.assignRandom');
