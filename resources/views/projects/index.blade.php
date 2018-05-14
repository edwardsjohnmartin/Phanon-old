@extends('layouts.app')

@section('content')
    <a href="{{url('/projects/create')}}" class="btn btn-primary">Create Project</a>
    <h1>Projects</h1>
    @if(count($projects) > 0)
        @foreach($projects as $project)
            <div class="well">
                <h3><a href="{{url('/projects/' . $project->id)}}">{{$project->name}}</a></h3>
                <small>Created on {{$project->created_at}} by {{$project->user->name}}</small>
            </div>
        @endforeach
        {{$projects->links()}}
    @else
        <p>No projects found</p>
    @endif
@endsection