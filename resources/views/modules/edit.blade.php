@extends('layouts.app')

@section('content')
    <h1>Edit Module</h1>
    {!! Form::open(['action' => ['ModulesController@update', $module->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $module->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($module->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($module->open_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', new \Carbon\Carbon($module->close_date))}}
            {{Form::time('close_time', date("H:i:s", strtotime($module->close_date)))}}
        </div>

        @if(count($lessons) > 0)
            <div class="form-group">
                <label>Select which lessons you want in the module</label>
                <select id="lessons" name="lessons[]" multiple class="form-control">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}" @if(in_array($lesson->id, $module_lesson_ids)) Selected @endif>{{$lesson->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#lessons').multiselect({
                nonSelectedText: 'Select Lesson',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection