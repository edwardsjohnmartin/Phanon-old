<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Course;
use App\Project;
use App\TeamProjectProgress;
use App\Enums\Roles;
use App\User;
use Auth;

class TeamsController extends Controller
{
    /**
     * Store a newly created team in the database for a specific course.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

            $success = "A team with the selected students was created.";

            return redirect(url('/courses/' . $course_id . '/teams'))->
                with('success', $success);
        } else {
            $error = "A team with those members already exists.";

            return redirect(url('/courses/' . $course_id . '/teams'))->
                with('error', $error);
        }
    }

    /**
     * Assigns the selected students to teams for a specific project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        // default name based on students; this is easier for tracking.
        // might be better in the future to make name based of student's 
        // real names.
        $teamName = "New Team";


        if($count % 2 == 0){
            for($i = 0; $i < $count; $i+=2){
                $newMembers = [];
                array_push($newMembers, $student_ids[$i]);
                array_push($newMembers, $student_ids[$i+1]);
                $teamName = "Team: ".$student_ids[$i]."-"
                    .$student_ids[$i+1];

                $team = Team::checkIfTeamExistsInCourse($newMembers, $course_id);

                if(!$team){
                    $team = new Team();
                    // default name based on students; this is easier for tracking.
                    $team->name = $teamName ;
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
                    // default name based on students; this is easier for tracking.
                    $teamName = "Team: ".$student_ids[$i]."-"
                    .$student_ids[$i+1]."-".$student_ids[$i+2];
                } else {
                    array_push($newMembers, $student_ids[$i]);
                    array_push($newMembers, $student_ids[$i+1]);
                    // default name based on students; this is easier for tracking.
                    $teamName = "Team: ".$student_ids[$i]."-"
                    .$student_ids[$i+1];
                }

                $team = Team::checkIfTeamExistsInCourse($newMembers, $course_id);

                if(!$team){
                    // increment default name; this is easier for tracking.
                    $team = Team::makeTeam($course_id, $newMembers,
                        $teamName);
                }

                array_push($teams, $team->id);
            }
        }

        $project = Project::find($project_id);
        $project->assignTeams($teams);
                
        $version =  $request->input('version');
        if(is_null($version)) $version = "full";

        if($version == "modal"){
            $retObject = (Object)["type"=>"error","identifier"=>"",
                "message"=>"","html"=>""];
             $retObject->type = "success";
            $retObject->message = "Teams have been randomly assigned.";
            $retObject->identifier = "modal"; // show results back in modal.
            //$role = $project->module->concept->course->getUsersRole(auth()->user()->id);
            
            $retObject->html = view("teams/teamsList",['project' => $project])->render();
            return response()->json($retObject);

        }else{
            return redirect(url('/projects/' . $project_id . '/teams'))->
                with('success', 'The ids array shuffled is: ' . print_r($student_ids, true));
        }
    }

    /**
     * Shows the team members.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForProject($id)
    {
        $project = Project::find($id);
        return view('teams.teamsList',['project'=>$project]);
    }

    /**
     * Shows the team member login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForm()
    {
        return view('teams.login');
    }

    public function loginModal()
    {
        $url = $_GET['url'];
        $team_id = $_GET['teamid'];
        return view('teams.loginform',['url' => $url,'team_id' => $team_id]);
    }

    /**
     * Attempt to validate a team member login.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $no_redirect = isset($_GET['noredirect']) && $_GET['noredirect'];
        $ret_message;
        $credentials = $request->only('email', 'password');
        $team_id = $request->only('teamid');


        if(Auth::validate($credentials)){
            $user = User::where('email', $credentials['email'])->first();
            $valid_member = false;

            if(session()->exists('members')){
                $members = session('members');
            } else {
                $members = [];
            }

            $team = Team::find($team_id)->first();

            $valid_members = $team->members;

            foreach($valid_members as $member){
                if($member->id == $user->id){
                    $valid_member = true;
                }
            }

            if($valid_member){
                // Validate the new user isnt the currently logged-in user
                if($user->id == auth()->user()->id){
                    $ret_message = $this->handleMessage($no_redirect,'teams/login',
                        "error", $user->name." is already logged in.",$user->id);
                }else{
                    $already_there = false;
                    // Validate the new user isn't already in the session as a member
                    foreach($members as $member){
                        if($member->id == $user->id){
                            $already_there = true;
                        }
                    }

                    if(!$already_there){
                        //need to only put the user here once.
                        array_push($members, $user);
                        session(['members' => $members]);
                    
                    $ret_message =  $this->handleMessage($no_redirect,'teams/manage',
                        "success", $user->name." successfully logged in.",$user->id);
                    }else{
                         $ret_message =  $this->handleMessage($no_redirect,'teams/login',
                                "error", $user->name." is already logged in.",$user->id);
                    }
                }
            }else{
                $ret_message =  $this->handleMessage($no_redirect,'teams/manage',
                    "error", $user->name." is not a valid member of this project team.",$user->id);
            }
        } else {
                $ret_message =  $this->handleMessage($no_redirect,'teams/login',
                    "error",  "Login failed for ".request("email"));
        }

        if($no_redirect){
            // convert to json for faster processing
            return response()->json($ret_message);
        }else{
            return $ret_message;
        }
    }
    /**
     * Summary of handleMessage
     * @param mixed $is_not_redirect true if the response should not redirect to a view
     * @param mixed $url url to redirect to if is_not_redirect is false.
     * @param mixed $type success|error type of message
     * @param mixed $message message to show user
     * @param mixed $assoc_id  id of object associated with message, only used if is_not_redirect is true
     * @return \Illuminate\Http\RedirectResponse|object
     */
    public function handleMessage($is_not_redirect,$url,$type,$message,$assoc_id = -1){
        if($is_not_redirect){
            // convert messages to objects so that they serialize to JSON on the other end.
            return (object)["type" => $type, "message" =>  $message, "userid" => $assoc_id];
        }else{
            return redirect(url($url))->with($type, $message);
        }
    }

    /**
     * Logs out a team member and updates the team members in the session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout($member_id)
    {
        $no_redirect = isset($_GET['noredirect']) && $_GET['noredirect'];
        $ret_message = null;

        if(session()->exists('members')){
            $members = session('members');

            $newMembers = [];
            $logged_user_out = false;
            $user_out = null;

            foreach($members as $member){
                if($member->id != $member_id){
                    array_push($newMembers, $member);
                } else {
                    $logged_user_out = true;
                    $user_out = $member;
                }
            }

            if($logged_user_out){
                session(['members' => $newMembers]);
                $ret_message = $this->handleMessage($no_redirect,'teams/manage',
                    "success", $user_out->name." logged out successfully.",$user_out->id);
            } else {
                $ret_message = $this->handleMessage($no_redirect,'teams/manage',
                    "error", "Could not find team member to log them out.");
            }
        }else{
            $ret_message = $this->handleMessage($no_redirect,'teams/manage',
                "error", "Could not find team member to log them out.");
        }
        if($no_redirect){
            return response()->json($ret_message);
        }else{
            return $ret_message;
        }
    }

    /**
     * Show the form for viewing the logged-in team members and logging-out team members.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
