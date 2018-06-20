@extends('layouts.app')

@section('content')
    <a href="{{url('/modules')}}" class="btn btn-default">Go Back</a>
    <h1>{{$module->name}}</h1>
    <div>
        <label>Concept</label>
        @if(!is_null($module->concept))
            <p>{{$module->concept->name}}</p>
        @else
            <p>Not contained in a concept</p>
        @endif
    </div>
    <div>
        <label>Open Date</label>
        <p>{{$module->getOpenDate()}}</p>
    </div>
    <div>
        <label>Contents</label>
        @if(count($lessonsAndProjects) > 0)
            <ul class="list-group">
            @foreach($lessonsAndProjects as $item)
                @if(is_a($item, 'App\Lesson'))
                    <a href="{{url('/lessons/' . $item->id)}}"><li class="list-group-item">{{$item->name}}</li></a>
                @else
                    <a href="{{url('/projects/' . $item->id)}}"><li class="list-group-item">{{$item->name}}</li></a>
                @endif
            @endforeach
            </ul>
        @else
            <p>This module does not contain any contents</p>
        @endif
    </div>
    <div>
        <small>Author: {{$module->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$module->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$module->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $module->user_id)
            <a href="{{url('/modules/' . $module->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ModulesController@destroy', $module->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection