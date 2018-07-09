<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Course;
use App\Project;
use App\Enums\Roles;

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

        $course = Course::find($course_id);
        $students = $course->getUsersByRole(Roles::id(Roles::STUDENT));

        $ids = [];
        foreach($students as $student){
            array_push($ids, $student->id);
        }

        shuffle($ids);

        $count = count($ids);

        $teams = [];

        if($count % 2 == 0){
            for($i = 0; $i < $count; $i+=2){
                $newMembers = [];
                array_push($newMembers, $ids[$i]);
                array_push($newMembers, $ids[$i+1]);

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
                    array_push($newMembers, $ids[$i]);
                    array_push($newMembers, $ids[$i+1]);
                    array_push($newMembers, $ids[$i+2]);
                } else {
                    array_push($newMembers, $ids[$i]);
                    array_push($newMembers, $ids[$i+1]);
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

        return redirect(url('/projects/' . $project_id . '/partners'))->
            with('success', 'The ids array shuffled is: ' . print_r($ids, true));
    }
}
