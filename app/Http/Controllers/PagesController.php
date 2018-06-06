<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Course;
use App\Enums\Permissions;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PagesController extends Controller
{
    /**
     * Displays the websites home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Displays a test page to test python code.
     *
     * @return \Illuminate\Http\Response
     */
    public function sandbox()
    {
        return view('pages.sandbox');
    }

    /**
     * Displays a summary of a users resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $coursesToShow = null;

        //TODO: Really need a better permission set for this.
        if ($user->hasPermissionTo(Permissions::ADMIN)){
            $coursesToShow = Course::paginate(10);
        } else{
            $coursesToShow = $user->courses;
        }

        //DEMO: Show how to get various courses for a user
        // This will return all courses the user has a role in
        $inCourses = $user->inCourses;
        $teacherCourses = $user->teachingCourses; // Teacher role
        $taCourses = $user->assistingCourses; // Teaching Assistant role
        $studentCourses = $user->takingCourses; // Student role

        return view('pages.dashboard')->
            with('courses', $coursesToShow);
    }

    /**
     * 
     */
    public function flow($id)
    {
        $course = Course::find($id);
        return view('pages.flow')->
            with('course', $course);
        //return view('pages.flow');
    }
}