@extends('layouts.app')

@section('content')
    <h1>Edit Lesson</h1>
    {!! Form::open(['action' => ['LessonsController@update', $lesson->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($lesson->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($lesson->open_date)))}}
        </div>

        @if(count($exercises) > 0)
            <div class="form-group">
                <label>Select which exercises you want in the lesson</label>
                <select id="exercises" name="exercises[]" multiple class="form-control">
                    @foreach($exercises as $exercise)
                        <option value="{{$exercise->id}}">{{$exercise->prompt}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No exercises exist</p>
        @endif

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#exercises').multiselect({
                nonSelectedText: 'Select Exercise',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection