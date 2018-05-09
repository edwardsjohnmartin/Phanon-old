<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        return view('pages.dashboard')->with('courses', $user->courses);
    }
}
