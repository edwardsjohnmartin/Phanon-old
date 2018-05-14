@extends('layouts.app')

@section('content')
    <a href="{{url('/modules/create')}}" class="btn btn-primary">Create Module</a>
    <h1>Modules</h1>
    @if(count($modules) > 0)
        @foreach($modules as $module)
            <div class="well">
                <h3><a href="{{url('/modules/' . $module->id)}}">{{$module->name}}</a></h3>
                @if(!is_null($module->course))
                    <p>Contained in the course: <a href="{{url('/courses/' . $module->course->id)}}">{{$module->course->name}}</a></p>
                @else
                    <p>Not contained in a course</p>
                @endif
                <p>Contains {{count($module->lessons)}} lessons</p>
                <p>Contains {{count($module->projects)}} projects</p>
                <small>Created on {{$module->created_at}} by {{$module->user->name}}</small>
            </div>
        @endforeach
        {{$modules->links()}}
    @else
        <p>No modules found</p>
    @endif
@endsection