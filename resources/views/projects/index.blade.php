@extends('layouts.app')

@section('content')
    @can('projects.create')
    <a href="{{url('/projects/create')}}" class="btn btn-primary">Create Project</a>
    @endcan
    <h1>Projects</h1>
    @if(count($projects) > 0)
        @foreach($projects as $project)
            <div class="well">
                <h3><a href="{{url('/projects/' . $project->id)}}">{{$project->name}}</a></h3>
                @if(!is_null($project->module))
                    <p>Contained in the module: <a href="{{url('/modules/' . $project->module->id)}}">{{$project->module->name}}</a></p>
                @else
                    <p>Not contained in a module</p>
                @endif
                <small>Created on {{$project->created_at}} by {{$project->user->name}}</small>
            </div>
        @endforeach
        {{$projects->links()}}
    @else
        <p>No projects found</p>
    @endif
@endsection