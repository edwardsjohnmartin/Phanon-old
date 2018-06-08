@extends('layouts.app')

@section('content')
<a href="{{url('/courses')}}" class="btn btn-default">Go Back</a>
<a href="{{url('/courses/' . $course->id . '/fullview')}}" class="btn btn-default">Full View</a>
<h1>{{$course->name}}</h1>
<div>
    <label>Open Date</label>
    <p>{{$course->getOpenDate()}}</p>
</div>
<div>
    <label>Close Date</label>
    <p>{{$course->getCloseDate()}}</p>
</div>
<div>
    <label>Concepts</label>
    @if(count($course->concepts()) > 0)
    <ul class="list-group">
        @foreach($course->concepts() as $concept)
        <li class="list-group-item">
            <a href="{{url('/concepts/' . $concept->id)}}">{{$concept->name}}</a>
            @foreach($concept->modules() as $module)
                @component('modules/partial',['module' => $module])
                                @endcomponent
            @endforeach
        </li>
        @endforeach
    </ul>
    @else
    <p>This course does not contain any concepts</p>
    @endif
</div>
<div>
    <small>Author: {{$course->user->name}}</small>
</div>
<div>
    <small>Created On: {{$course->created_at}}</small>
</div>
<div>
    <small>Last Updated At: {{$course->updated_at}}</small>
</div>
<hr />
@if(!Auth::guest())
@if(Auth::user()->id == $course->user_id)
<a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-default">Edit</a>



{!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection