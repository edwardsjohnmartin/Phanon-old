@extends('layouts.app')

@section('content')
    <a href="/exercises" class="btn btn-default">Go Back</a>
    <div>
        <label>Prompt</label>
        <textarea class="form-control rounded-0" readonly>{{$exercise->prompt}}</textarea>
    </div>
    <div>
        <label>Pre-Code</label>
        <textarea class="form-control rounded-0" readonly>{{$exercise->pre_code}}</textarea>
    </div>
    <div>
        <label>Start Code</label>
        <textarea class="form-control rounded-0" readonly>{{$exercise->start_code}}</textarea>
    </div>
    <div>
        <label>Test Code</label>
        <textarea class="form-control rounded-0" readonly>{{$exercise->test_code}}</textarea>
    </div>
    <small>Created on {{$exercise->created_at}}</small>
    <hr>
    @if(!Auth::guest())
        <a href="/exercises/{{$exercise->id}}/edit" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['ExercisesController@destroy', $exercise->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection