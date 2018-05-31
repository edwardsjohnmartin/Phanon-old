@extends('layouts.app')

@section('content')
    <a href="{{url('/lessons')}}" class="btn btn-default">Go Back</a>
    <a href="{{url('/lessons/' . $lesson->id . '/clone/')}}" class="btn btn-default">Clone</a>
    <h1>{{$lesson->name}}</h1>
    <div>
        <label>Exercises</label>
        @if(count($exercises) > 0)
            @foreach($exercises as $exercise)
                <div class="well">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#prompt_{{$exercise->id}}">Prompt</a></li>
                        <li><a data-toggle="tab" href="#rawprompt_{{$exercise->id}}">Raw Prompt</a></li>
                        <li><a data-toggle="tab" href="#precode_{{$exercise->id}}">Pre Code</a></li>
                        <li><a data-toggle="tab" href="#startcode_{{$exercise->id}}">Start Code</a></li>
                        <li><a data-toggle="tab" href="#testcode_{{$exercise->id}}">Test Code</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="prompt_{{$exercise->id}}" class="tab-pane fade in active">
                            <h3>Prompt</h3>
                            <p>{!!$exercise->prompt!!}</p>
                        </div>
                        <div id="rawprompt_{{$exercise->id}}" class="tab-pane fade">
                            <h3>Raw Prompt</h3>
                            <p>{{$exercise->prompt}}</p>
                        </div>
                        <div id="precode_{{$exercise->id}}" class="tab-pane fade">
                            <h3>Pre Code</h3>
                            <p>{{$exercise->pre_code}}</p>
                        </div>
                        <div id="startcode_{{$exercise->id}}" class="tab-pane fade">
                            <h3>Start Code</h3>
                            <p>{{$exercise->start_code}}</p>
                        </div>
                        <div id="testcode_{{$exercise->id}}" class="tab-pane fade">
                            <h3>Test Code</h3>
                            <p>{{$exercise->test_code}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif 
    </div>
    <div>
        <small>Author: {{$lesson->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$lesson->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$lesson->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        <a href="{{url('/lessons/' . $lesson->id . '/edit')}}" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['LessonsController@destroy', $lesson->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection