@extends('layouts.app')

@section('content')
    <a href="{{url('/concepts')}}" class="btn btn-default">Go Back</a>
    
    <h1>{{$concept->name}}</h1>
    <div>
        <label>Containing Course</label>
        @if(!empty($concept->course))
            <a href="{{url('/courses/' . $concept->course->id)}}"><p>{{$concept->course->name}}</p></a>
        @else
            <p>This concept is not in a course</p>
        @endif
    </div>
    <div>
        <label>Modules</label>
        @if(count($concept->modules()) > 0)
            <ul class="list-group">
            @foreach($concept->modules() as $module)
                <a href="{{url('/modules/' . $module->id)}}"><li class="list-group-item">{{$module->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This concept does not contain any modules</p>
        @endif
    </div>
    <div>
        <small>Author: {{$concept->owner->name}}</small>
    </div>
    <div>
        <small>Created On: {{$concept->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$concept->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $concept->owner_id)
            <a href="{{url('/concepts/' . $concept->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ConceptsController@destroy', $concept->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection
