@extends('layouts.app')

@section('content')
    <a href="{{url('/exercises/create')}}" class="btn btn-primary">Create Exercise</a>
    <h1>Exercises</h1>
    @if(count($exercises) > 0)
        @foreach($exercises as $exercise)
            <div class="well">
                <h3><a href="{{url('/exercises/' . $exercise->id)}}">{{$exercise->prompt}}</a></h3>
                <small>Created on {{$exercise->created_at}}</small>
            </div>
        @endforeach
        {{$exercises->links()}}
    @else
        <p>No exercises found</p>
    @endif
@endsection