@extends('layouts.app')

@section('content')
    <h1>Courses</h1>
    @if(count($courses) > 0)
        @foreach($courses as $course)
            <div class="well">
                <h3><a href="/courses/{{$course->id}}">{{$course->name}}</a></h3>
                <small>Written on {{$course->created_at}}</small>
            </div>
        @endforeach
        {{$courses->links()}}
    @else
        <p>No courses found</p>
    @endif
@endsection