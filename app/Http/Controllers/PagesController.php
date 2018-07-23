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

        //TODO: Really need a better permission set for this.
        if ($user->hasPermissionTo(Permissions::ADMIN)){
            $coursesToShow = Course::paginate(10);
        } else{
            $coursesToShow = $user->courses()->get();
        }

        //DEMO: This will return all courses the user has a role in
        //NOTE: This does not include courses owned by the user that they do not have a role in
        //$coursesToShow = $user->enrolledCourses;

        return view('pages.dashboard')->
            with('courses', $coursesToShow);
    }
}
