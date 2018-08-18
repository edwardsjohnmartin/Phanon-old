@extends('layouts.app')

@section('content')
    @can('exercise.create')
        <a href="{{url('/exercises/create')}}" class="btn btn-primary">Create Exercise</a>
    @endcan

    <h1>Exercises</h1>
    @if(count($exercises) > 0)
        @foreach($exercises as $exercise)
            <div class="well">
                <h3>
                    <a href="{{url('/exercises/' . $exercise->id)}}">
                        @if(!is_null($exercise->type))
                            {{$exercise->type->prompt}}
                        @else
                            Empty prompt
                        @endif
                    </a>
                </h3>

                <label>Exercise Type</label>
                <p>{{get_class($exercise->type)}}</p>

                @if(!is_null($exercise->lesson))
                    <p>Contained in the lesson: <a href="{{url('/lessons/' . $exercise->lesson->id)}}">{{$exercise->lesson->name}}</a></p>
                @else
                    <p>Not contained in a lesson</p>
                @endif

                <small>Created on {{$exercise->created_at}} by {{$exercise->owner->name}}</small>
            </div>
        @endforeach
        {{$exercises->links()}}
    @else
        <p>No exercises found</p>
    @endif
@endsection
