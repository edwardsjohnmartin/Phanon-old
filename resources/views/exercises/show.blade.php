@extends('layouts.app')

@section('content')
    <a href="{{url('/exercises')}}" class="btn btn-default">Go Back</a>
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
    <div class="well">
        @if(count($exercise->lessons) > 0)
            <p>This exercise is contained in the following lessons</p>
            <ul class="list-group">
            @foreach($exercise->lessons as $lesson)
                <a href="{{url('/lessons/' . $lesson->id)}}"><li class="list-group-item">{{$lesson->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This exercise is not contained in any lessons</p>
        @endif
    </div>
    <small>Created on {{$exercise->created_at}}</small>
    <hr>
    @if(!Auth::guest())
        <a href="{{url('/exercises/' . $exercise->id . '/edit')}}" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['ExercisesController@destroy', $exercise->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection