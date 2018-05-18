<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Course;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PagesController extends Controller{
    public function index(){
        return view('pages.index');
    }

    public function sandbox(){
        return view('pages.sandbox');
    }

    public function dashboard(){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $coursesToShow = null;

        //TODO: Really need a better permission set for this.
        if ($user->hasPermissionTo("Administer roles & permissions")){
            $coursesToShow = Course::paginate(10);
        } else{
            $coursesToShow = $user->courses;
        }

        return view('pages.dashboard')->with('courses', $coursesToShow);
    }
}
