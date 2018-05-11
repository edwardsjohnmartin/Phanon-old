@extends('layouts.app')

@section('content')
    <a href="{{url('/lessons/create')}}" class="btn btn-primary">Create Lesson</a>
    <h1>Lessons</h1>
    @if(count($lessons) > 0)
        @foreach($lessons as $lesson)
            <div class="well">
                <h3><a href="{{url('/lessons/' . $lesson->id)}}">{{$lesson->name}}</a></h3>
                <p>Contains {{count($lesson->exercises)}} Exercises</p>
                <p>Created on {{$lesson->created_at}}</p>
            </div>
        @endforeach
        {{$lessons->links()}}
    @else
        <p>No lessons found</p>
    @endif
@endsection