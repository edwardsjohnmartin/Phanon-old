@extends('layouts.app')

@section('content')
    <a href="{{url('/courses')}}" class="btn btn-default">Go Back</a>
    <h1>{{$course->name}}</h1>
    <div>
        <label>Open Date</label>
        <p>{{$course->getOpenDate()}}</p>
    </div>
    <div>
        <label>Close Date</label>
        <p>{{$course->getCloseDate()}}</p>
    </div>
    <div>
        <label>Concepts</label>
        @if(!empty($course->concepts()))
            <ul class="list-group">
            @foreach($course->concepts() as $concept)
                <li class="list-group-item"><a href="{{url('/concepts/' . $concept->id)}}">{{$concept->name}}</a></li>
            @endforeach
            </ul>
        @else
            <p>This course does not contain any concepts</p>
        @endif
    </div>
    <div>
        <label>Teachers</label>
        @if(count($course->teachers) > 0)
            <ul class="list-group">
            @foreach($course->teachers as $teacher)
                <li class="list-group-item">{{$teacher->name}}</li>
            @endforeach
            </ul>
        @else
            <p>There are no teachers in this course</p>
        @endif
        <label>Teaching Assistants</label>
        @if(count($course->assistants) > 0)
            <ul class="list-group">
            @foreach($course->assistants as $assistant)
                <li class="list-group-item">{{$assistant->name}}</li>
            @endforeach
            </ul>
        @else
            <p>There are no teaching assistants in this course</p>
        @endif
        <label>Students</label>
        @if(count($course->students) > 0)
            <ul class="list-group">
            @foreach($course->students as $student)
                <li class="list-group-item">{{$student->name}}</li>
            @endforeach
            </ul>
        @else
            <p>There are no students in this course</p>
        @endif
    </div>
    <div>
        <small>Author: {{$course->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$course->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$course->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $course->user_id)
            <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection