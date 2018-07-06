@extends('layouts.app')

@section('content')
    <a href="{{url('/projects')}}" class="btn btn-default">Go Back</a>
    <h1>{{$project->name}}</h1>
    <div>
        <label>Module</label>
        @if(!is_null($project->module))
            <p><a href="{{url('/modules/' . $project->module->id)}}">{{$project->module->name}}</a></p>
        @else
            <p>Not contained in a module</p>
        @endif
    </div>
    <div>
        <label>Open Date</label>
        <p>{{$project->getOpenDate()}}</p>
    </div>
    <div>
        <label>Close Date</label>
        <p>{{$project->getOpenDate()}}</p>
    </div>
    <div>
        <label>Prompt</label>
        <p>{!! $project->prompt !!}</p>
    </div>
    <div>
        <label>Pre-Code</label>
        <p>{{$project->pre_code}}</p>
    </div>
    <div>
        <label>Start Code</label>
        <p>{{$project->start_code}}</p>
    </div>
    <div>
        <label>Partners Enabled</label>
        <p>{{$project->hasPartners(true)}}</p>
    </div>
    <div>
        <small>Author: {{$project->owner->name}}</small>
    </div>
    <div>
        <small>Created On: {{$project->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$project->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $project->owner_id)
            <a href="{{url('/projects/' . $project->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ProjectsController@destroy', $project->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection