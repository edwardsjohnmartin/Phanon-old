@extends('layouts.app')

@section('content')
    <h1>Edit Course</h1>
    {!! Form::open(['action' => ['CoursesController@update', $course->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $course->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($course->modules) > 0)
            <div>
                <label>Modules in the course</label>
                <ul class="list-group">
                @foreach($course->modules as $module)
                    <li class="list-group-item">{{$module->name}}</li>
                @endforeach
                </ul>
            </div>
        @endif

        @if(count($unused_modules) > 0)
            <div class="form-group">
                <label>Select which modules you want to add to the course</label>
                <select id="unused_modules" name="unused_modules[]" multiple class="form-control">
                    @foreach($unused_modules as $unused_module)
                        <option value="{{$unused_module->id}}" @if(in_array($unused_module->id, $course_module_ids)) @endif>{{$unused_module->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No modules exist</p>
        @endif

        @if(count($used_modules) > 0)
            <div class="form-group">
                <label>Modules used in other courses</label>
                <table class="table">
                    <tr>
                        <th>Module</th>
                        <th>Course</th>
                        <th>Clone</th>
                    </tr>
                    <tr>
                    @foreach($used_modules as $used_module)
                        <td>{{$used_module->name}}</td>
                        <td>{{$used_module->course->name}}</td>
                        <td><a href="" class="btn btn-default">Clone Module</a></td>
                    @endforeach
                    </tr>
                </table>
            </div>
        @endif

        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#unused_modules').multiselect({
                nonSelectedText: 'Select Module',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection