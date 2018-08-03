@extends('layouts.app')

@section('content')
    @can('course.create')
        <a href="{{url('/courses/create')}}" class="btn btn-primary">Create Course</a>
    @endcan
    
    <h1>Courses</h1>
    @if(count($courses) > 0)
        @foreach($courses as $course)
            <div class="well">
                <h3><a href="{{url('/courses/' . $course->id)}}">{{$course->name}}</a></h3>
                <p>Contains {{count($course->concepts())}} concepts</p>
                <small>Created on {{$course->created_at}} by {{$course->owner->name}}</small>
                <div>
                    <a class="btn btn-primary" href="{{url('/courses/' . $course->id . '/copy')}}">Create Copy</a>
                    <a class="btn btn-primary" href="{{url('/flow/' . $course->id)}}">Course Flow</a>
                </div>
            </div>
        @endforeach
        {{$courses->links()}}
    @else
        <p>No courses found</p>
    @endif
@endsection
