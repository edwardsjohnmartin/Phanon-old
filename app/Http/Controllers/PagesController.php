<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

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

        return view('pages.dashboard')->with('courses', $user->courses);
    }
}
