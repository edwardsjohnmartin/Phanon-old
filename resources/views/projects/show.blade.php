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
        <p>{{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $project->open_date), 'm/d/Y h:i a')}}</p>
    </div>
    <div>
        <label>Close Date</label>
        <p>{{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $project->close_date), 'm/d/Y h:i a')}}</p>
    </div>
    <div>
        <label>Prompt</label>
        <label class="form-control rounded-0" readonly>{{$project->prompt}}</textarea>
    </div>
    <div>
        <label>Pre-Code</label>
        <label class="form-control rounded-0" readonly>{{$project->pre_code}}</textarea>
    </div>
    <div>
        <label>Start Code</label>
        <label class="form-control rounded-0" readonly>{{$project->start_code}}</textarea>
    </div>
    <div>
        <small>Author: {{$project->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$project->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$project->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $project->user_id)
            <a href="{{url('/projects/' . $project->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ProjectsController@destroy', $project->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection