@extends('layouts.app')

@section('content')
    <a href="{{url('/lessons/create')}}" class="btn btn-primary">Create Lesson</a>
    <h1>Lessons</h1>
    @if(count($lessons) > 0)
        @foreach($lessons as $lesson)
            <div class="well">
                <h3><a href="{{url('/lessons/' . $lesson->id)}}">{{$lesson->name}}</a></h3>
                @if(!is_null($lesson->module))
                    <p>Contained in the module: <a href="{{url('/modules/' . $lesson->module->id)}}">{{$lesson->module->name}}</a></p>
                @else
                    <p>Not contained in a module</p>
                @endif
                <p>Contains {{count($lesson->exercises())}} exercises</p>
                <small>Created on {{$lesson->created_at}} by {{$lesson->user->name}}</small>
            </div>
        @endforeach
        {{$lessons->links()}}
    @else
        <p>No lessons found</p>
    @endif
@endsection