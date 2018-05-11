@extends('layouts.app')

@section('content')
    <h1>Edit Course</h1>
    {!! Form::open(['action' => ['CoursesController@update', $course->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $course->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($modules) > 0)
            <div class="form-group">
                <label>Select which modules you want in the course</label>
                <select id="modules" name="modules[]" multiple class="form-control">
                    @foreach($modules as $module)
                        <option value="{{$module->id}}" @if(in_array($module->id, $course_module_ids)) Selected @endif>{{$module->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#modules').multiselect({
                nonSelectedText: 'Select Module',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection