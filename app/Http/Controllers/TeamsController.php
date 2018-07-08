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

        //TODO: Add a check here to see if a team in this course with these members already exists

        $team = new Team();
        $team->name = 'New Team';
        $team->course_id = $course_id;
        $team->save();

        $team->addMembers($students);

        return redirect(url('/courses/' . $course_id . '/teams'))->
            with('success', 'The team id is ' . $team->id);
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
                $team = Team::makeTeam($course_id, [$ids[$i], $ids[$i+1]]);
                array_push($teams, $team->id);
            }
        } else {
            for($i = 0; $i < $count-1; $i+=2){
                if($count - $i == 3){
                    $team = Team::makeTeam($course_id, [$ids[$i], $ids[$i+1], $ids[$i+2]]);
                    array_push($teams, $team->id);
                } else {
                    $team = Team::makeTeam($course_id, [$ids[$i], $ids[$i+1]]);
                    array_push($teams, $team->id);
                }
            }
        }

        $project = Project::find($project_id);
        $project->assignTeams($teams);

        return redirect(url('/projects/' . $project_id . '/partners'))->
            with('success', 'The ids array shuffled is: ' . print_r($ids, true));
    }
}
