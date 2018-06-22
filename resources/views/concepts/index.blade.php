@extends('layouts.app')

@section('content')
    @can('concepts.create')
    <a href="{{url('/concepts/create')}}" class="btn btn-primary">Create Concept</a>
    @endcan
    <h1>Concepts</h1>
    @if(count($concepts) > 0)
        @foreach($concepts as $concept)
            <div class="well">
                <h3><a href="{{url('/concepts/' . $concept->id)}}">{{$concept->name}}</a></h3>
                @if(!empty($concept->course))
                    <p>Contained in the course <a href="{{url('/courses/' . $concept->course->id)}}">{{$concept->course->name}}</a></p>
                @else
                    <p>This concept is not in a course</p>
                @endif
                <p>Contains {{count($concept->modules())}} modules</p>
                <small>Created on {{$concept->created_at}} by {{$concept->owner->name}}</small>
            </div>
        @endforeach
        {{$concepts->links()}}
    @else
        <p>No concepts found</p>
    @endif
@endsection