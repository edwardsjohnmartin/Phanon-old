@extends('layouts.app')

@section('content')
    <a class="btn btn-default" href="{{url('/courses/' . $course->id . '/copy')}}">copy</a>

    <h2>{{$course->name}}</h2>
    @foreach($course->modules as $module)
        <h3>{{$module->name}}</h3>
        @foreach($module->lessons as $lesson)
            <h4>{{$lesson->name}}</h4>
            @foreach($lesson->exercises as $exercise)
                <h5>{{$exercise->prompt}}</h5>
            @endforeach
        @endforeach
        @foreach($module->projects as $project)
            <h4>{{$project->name}}</h4>
        @endforeach
    @endforeach
    <div>
        <small>Author: {{$course->owner->name}}</small>
    </div>
    <div>
        <small>Created On: {{$course->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$course->updated_at}}</small>
    </div>
@endsection