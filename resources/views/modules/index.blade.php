@extends('layouts.app')

@section('content')
    @can('modules.create')
    <a href="{{url('/modules/create')}}" class="btn btn-primary">Create Module</a>
    @endcan
    <h1>Modules</h1>
    @if(count($modules) > 0)
        @foreach($modules as $module)
            <div class="well">
                <h3><a href="{{url('/modules/' . $module->id)}}">{{$module->name}}</a></h3>
                @if(!is_null($module->concept))
                    <p>Contained in the concept: <a href="{{url('/concepts/' . $module->concept->id)}}">{{$module->concept->name}}</a></p>
                @else
                    <p>Not contained in a concept</p>
                @endif
                <p>Contains {{count($module->unorderedLessons)}} lessons</p>
                <p>Contains {{count($module->unorderedProjects)}} projects</p>
                <small>Created on {{$module->created_at}} by {{$module->user->name}}</small>
            </div>
        @endforeach
        {{$modules->links()}}
    @else
        <p>No modules found</p>
    @endif
@endsection