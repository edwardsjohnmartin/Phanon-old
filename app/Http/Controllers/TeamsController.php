<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Course;
use App\Project;
use App\Enums\Roles;
use App\User;
use Auth;

class TeamsController extends Controller
{
    public function createTeam(Request $request)
    {
        $course_id = $request->input('course_id');
        $students = $request->input('students');

        if(count($students) <= 1){
            return redirect(url('/courses/' . $course_id . '/teams'))->
                with('error', 'There must be at least two students on the team.');
        }

        $team = Team::checkIfTeamExistsInCourse($students, $course_id);

        if(!$team){
            $team = new Team();
            $team->name = 'New Team';
            $team->course_id = $course_id;
            $team->save();
    
            $team->addMembers($students);

            $success = "A team with those members was created.";

            return redirect(url('/courses/' . $course_id . '/teams'))->
                with('success', $success);
        } else {
            $error = "A team with those members already exists.";

            return redirect(url('/courses/' . $course_id . '/teams'))->
                with('error', $error);
        }
    }

    public function assignRandomTeams(Request $request)
    {
        $project_id = $request->input('project_id');
        $course_id = $request->input('course_id');
        $student_ids = $request->input('students');

        if(count($student_ids) < 2){
            return redirect(url('/projects/' . $project_id . '/teams'))->
                with('error', 'At least two students must be selected to assign teams');
        }

        shuffle($student_ids);

        $count = count($student_ids);

        $teams = [];

        if($count % 2 == 0){
            for($i = 0; $i < $count; $i+=2){
                $newMembers = [];
                array_push($newMembers, $student_ids[$i]);
                array_push($newMembers, $student_ids[$i+1]);

                $team = Team::checkIfTeamExistsInCourse($newMembers, $course_id);
                
                if(!$team){
                    $team = new Team();
                    $team->name = 'New Team';
                    $team->course_id = $course_id;
                    $team->save();
            
                    $team->addMembers($newMembers);
                }

                array_push($teams, $team->id);
            }
        } else {
            for($i = 0; $i < $count-1; $i+=2){
                $newMembers = [];

                if($count - $i == 3){
                    array_push($newMembers, $student_ids[$i]);
                    array_push($newMembers, $student_ids[$i+1]);
                    array_push($newMembers, $student_ids[$i+2]);
                } else {
                    array_push($newMembers, $student_ids[$i]);
                    array_push($newMembers, $student_ids[$i+1]);
                }

                $team = Team::checkIfTeamExistsInCourse($newMembers, $course_id);

                if(!$team){
                    $team = Team::makeTeam($course_id, $newMembers);
                }
                
                array_push($teams, $team->id); 
            }
        }

        $project = Project::find($project_id);
        $project->assignTeams($teams);

        return redirect(url('/projects/' . $project_id . '/teams'))->
            with('success', 'The ids array shuffled is: ' . print_r($student_ids, true));
    }

    public function showLoginForm()
    {
        return view('teams.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::validate($credentials)){
            $user = User::where('email', $credentials['email'])->first();

            // Validate the new user isnt the currently logged-in user
            if($user->id == auth()->user()->id){
                return redirect(url('teams/login'))->
                    with('error', 'That user is already logged in');
            }

            if(session()->exists('members')){
                $members = session('members');
            } else {
                $members = [];
            }

            // Validate the new user isn't already in the session as a member
            foreach($members as $member){
                if($member->id == $user->id){
                    return redirect(url('teams/login'))->
                        with('error', 'That user is already logged in');
                }
            }

            array_push($members, $user);

            session(['members' => $members]);

            return redirect(url('teams/manage'))->
                with('success', 'Team member logged in');
        } else {
            return redirect(url('teams/login'))->
                with('error', 'Incorrect credentials');
        }        
    }

    public function logout($member_id)
    {
        if(session()->exists('members')){
            $members = session('members');

            $newMembers = [];
            $logged_user_out = false;

            foreach($members as $member){
                if($member->id != $member_id){
                    array_push($newMembers, $member);
                } else {
                    $logged_user_out = true;
                }
            }

            if($logged_user_out){
                session(['members' => $newMembers]);

                return redirect(url('/teams/manage'))->
                    with('success', 'Team member logged out successfully');
            } else {
                return redirect(url('/teams/manage'))->
                    with('error', 'Could not find team member to log them out');
            }
        }
    }

    public function manage()
    {
        if(session()->exists('members')){
            $members = session('members');
        } else {
            $members = [];
        }

        return view('teams.manage')->
            with('members', $members);
    }
}
