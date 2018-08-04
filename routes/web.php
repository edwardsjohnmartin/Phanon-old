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
Route::get('/about', 'PagesController@about')->name('about');
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

Route::get('/code/lesson/{lesson_id}', 'CodeController@lesson');
Route::get('/code/module/{module_id}', 'CodeController@module');

Route::get('/projects/{id}/teams', 'ProjectsController@teams')->name('project.teams');
Route::get('/courses/{id}/teams', 'CoursesController@teams')->name('course.teams');
Route::post('/teams', 'TeamsController@createTeam')->name('teams.create');
Route::post('/assignRandomTeams', 'TeamsController@assignRandomTeams')->name('teams.assignRandom');

Route::get('/teams/login', 'TeamsController@loginForm')->name('teams.login');
Route::get('/teams/loginform', 'TeamsController@loginModal')->name('teams.loginform');
Route::post('/teams/login', 'TeamsController@login');
Route::post('/teams/logout/{member_id}', 'TeamsController@logout')->name('teams.logout');

Route::get('/teams/manage', 'TeamsController@manage')->name('teams.manage');

Route::get('/flow/course/create', 'FlowController@create')->name('flow.create');
Route::get('/flow/{id}', 'FlowController@show')->name('flow.show');

Route::post('/ajax/conceptcreate', 'FlowController@createConcept');
Route::post('/ajax/modulecreate', 'FlowController@createModule');
Route::post('/ajax/lessoncreate', 'FlowController@createLesson');
Route::post('/ajax/projectcreate', 'FlowController@createProject');
Route::post('/ajax/exercisecreate', 'CodeController@createExercise');

Route::post('/ajax/exercisecopy', 'CodeController@copyExercise');


Route::post('/ajax/projectedit', 'CodeController@editProject');
Route::post('/ajax/exerciseedit', 'CodeController@editExercise');
Route::post('/ajax/courseedit', 'FlowController@editCourse');


Route::post('/ajax/projectsurveycreate', 'ProjectSurveyResponsesController@createResponse');

Route::get('/courses/{course_id}/copy', 'CoursesController@copy')->name('courses.copy');

// this uses the new routes system where we do everything
//   via the associated Objects controller
Route::post('/exercises/move', 'ExercisesController@move');
Route::get('/lessons/miniEditForm/{id}', 'LessonsController@miniEditForm');
Route::get('/projects/miniEditForm/{id}', 'ProjectsController@miniEditForm');
Route::post('/lessons/updateSimple/', 'LessonsController@updateSimple')->name("lesson.updateSimple");

