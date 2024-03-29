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
    public function __construct()
    {
        // Use the 'auth' middleware to make sure a user is logged in
        // Use the 'clearance' middleware to check if a user has permission to access each function
        $this->middleware(['auth', 'clearance'])->except(['index', 'about']);
    }

    /**
     * Displays the website's home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Displays the page describing what the purpose of the site is.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('pages.about');
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

        // Get courses a user owns but may not have a role in
        $ownedCourses = $user->courses()->get();

        // Get courses a user has a role in but may not own
        $enrolledCourses = $user->enrolledCourses;

        // Combine the courses into a single collection
        $coursesToShow = $ownedCourses->merge($enrolledCourses);

        //TODO: Really need a better permission set for this.
        // if ($user->hasPermissionTo(Permissions::ADMIN)){
        //     $coursesToShow = Course::paginate(10);
        // } else{
        //     $coursesToShow = $user->enrolledCourses()->get();
        // }

        //DEMO: This will return all courses the user has a role in
        //NOTE: This does not include courses owned by the user that they do not have a role in
        //$coursesToShow = $user->enrolledCourses;

        return view('pages.dashboard')->
            with('courses', $coursesToShow);
    }
}
