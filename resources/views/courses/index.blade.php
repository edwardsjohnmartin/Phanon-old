@extends('layouts.app')

@section('content')
    <a href="/courses/create" class="btn btn-primary">Create Course</a>
    <h1>Courses</h1>
    @if(count($courses) > 0)
        @foreach($courses as $course)
            <div class="well">
                <h3><a href="/courses/{{$course->id}}">{{$course->name}}</a></h3>
                <small>Created on {{$course->created_at}} by {{$course->user->name}}</small>
            </div>
        @endforeach
        {{$courses->links()}}
    @else
        <p>No courses found</p>
    @endif
@endsection