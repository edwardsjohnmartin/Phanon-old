@extends('layouts.app')

@section('content')
    <div>
        <h1>Partner Assignments</h1>
    </div>

    <div>
        <h3><a href="{{url('code/project/' . $project->id)}}">Project: {{$project->name}}</a></h3>
    </div>

    <div>
        @component('partials.teamsTable', ['teams' => $project->teams])
        @endcomponent
    </div>

    @if($role->hasPermissionTo('team.create'))
        {!! Form::open(['id' => 'assignRandomTeams', 'action' => 'TeamsController@assignRandomTeams', 'method' => 'POST']) !!}
            
            <input name="project_id" value="{{$project->id}}" type="hidden")>
            <input name="course_id" value="{{$course->id}}" type="hidden")>
            
            {{Form::submit('Assign Random Teams', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
    @endif
@endsection
