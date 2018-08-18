@extends('layouts.app')

@section('content')
    <a href="{{url('/exercises')}}" class="btn btn-default">Go Back</a>
    <div>
        <label>Lesson</label>
        @if(!is_null($exercise->lesson))
            <p>{{$exercise->lesson->name}}</p>
        @else
            <p>Not contained in a lesson</p>
        @endif
    </div>

    @if(get_class($exercise->type) == "App\Code")
        <div>
            <label>Prompt</label>
            <p class="form-control rounded-0">{{$exercise->type->prompt}}</p>
        </div>
        <div>
            <label>Pre-Code</label>
            <p class="form-control rounded-0">{{$exercise->type->pre_code}}</p>
        </div>
        <div>
            <label>Start Code</label>
            <p class="form-control rounded-0">{{$exercise->type->start_code}}</p>
        </div>
        <div>
            <label>Test Code</label>
            <p class="form-control rounded-0">{{$exercise->type->test_code}}</p>
        </div>
        <div>
            <label>Solution</label>
            <p class="form-control rounded-0">{{$exercise->type->solution}}</p>
        </div>
    @elseif(get_class($exercise->type) == "App\Choice")
        <div>
            <label>Prompt</label>
            <p class="form-control rounded-0">{{$exercise->type->prompt}}</p>
        </div>
        <div>
            <label>Choices</label>
            @foreach($exercise->type->choicesAsArray() as $choice)
                <label class="form-control rounded-0" readonly>{{$choice}}</label>
            @endforeach
        </div>
        <div>
            <label>Solution</label>
            <p class="form-control rounded-0">{{$exercise->type->solutionText()}}</p>
        </div>
    @elseif(get_class($exercise->type) == "App\Scale")
        <div>
            <label>Prompt</label>
            <p class="form-control rounded-0">{{$exercise->type->prompt}}</p>
        </div>
        <div>
            <label>Number of Options</label>
            <p class="form-control rounded-0">{{$exercise->type->num_options}}</p>
        </div>
        <div>
            <label>Labels</label>
            @foreach($exercise->type->labelsAsArray() as $label)
                <label class="form-control rounded-0">{{$label}}</label>
            @endforeach
        </div>
    @endif
    <div>
        <small>Author: {{$exercise->owner->name}}</small>
    </div>
    <div>
        <small>Created On: {{$exercise->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$exercise->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $exercise->owner_id)
            <a href="{{url('/exercises/' . $exercise->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ExercisesController@destroy', $exercise->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection
