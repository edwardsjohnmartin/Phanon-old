@extends('layouts.app')

@section('content')
    <h1>Team Assignments</h1>

    <h2>Project: <a href="{{url('code/project/' . $project->id)}}">{{$project->name}}</a></h2>

    @if($role->hasPermissionTo('team.create'))
        <h3>Choose Students to be Assigned Teams</h3>

        {!! Form::open(['id' => 'assignRandomTeams', 'action' => 'TeamsController@assignRandomTeams', 'method' => 'POST']) !!}
            <div>
                <table class="table">
                    <tr>
                        <th>Students</th>
                        <th>Include</th>
                    </tr>
                    @foreach($students as $student)
                        <tr>
                            <td>{{$student->name}}</td>
                            <td><input name="students[]" value="{{$student->id}}" type="checkbox" checked></td>
                        </tr>
                    @endforeach
                </table>
            </div>
      
            <input name="project_id" value="{{$project->id}}" type="hidden")>
            <input name="course_id" value="{{$course->id}}" type="hidden")>
            
            {{Form::submit('Assign Teams', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}

        <div>
            <h3>Currently Assigned Teams</h3>

            @component('teams.teamsTable', ['teams' => $project->teams])
            @endcomponent
        </div>
    @else
        <div>
            <h3>Your Assigned Team</h3>

            @component('teams.teamsTable', ['teams' => $team])
            @endcomponent
        </div>
    @endif
@endsection
