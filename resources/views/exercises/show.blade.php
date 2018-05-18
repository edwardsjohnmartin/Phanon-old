@extends('layouts.app')

@section('content')
    <a href="{{url('/exercises')}}" class="btn btn-default">Go Back</a>
    <a href="{{url('/exercises/' . $exercise->id . '/clone/')}}" class="btn btn-default">Clone</a>
    <div>
        <label>Lesson</label>
        @if(!is_null($exercise->lesson))
            <p>{{$exercise->lesson->name}}</p>
        @else
            <p>Not contained in a lesson</p>
        @endif
    </div>
    <div>
        <label>Prompt</label>
        <label class="form-control rounded-0" readonly>{{$exercise->prompt}}</textarea>
    </div>
    <div>
        <label>Pre-Code</label>
        <label class="form-control rounded-0" readonly>{{$exercise->pre_code}}</textarea>
    </div>
    <div>
        <label>Start Code</label>
        <label class="form-control rounded-0" readonly>{{$exercise->start_code}}</textarea>
    </div>
    <div>
        <label>Test Code</label>
        <label class="form-control rounded-0">{{$exercise->test_code}}</textarea>
    </div>
    <div>
        <small>Author: {{$exercise->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$exercise->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$exercise->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $exercise->user_id)
            <a href="{{url('/exercises/' . $exercise->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ExercisesController@destroy', $exercise->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection