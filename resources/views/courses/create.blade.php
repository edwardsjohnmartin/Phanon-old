@extends('layouts.app')

@section('content')
    <h1>Create Course</h1>
    {!! Form::open(['action' => 'CoursesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <!-- <div class="form-group">
            {{Form::label('modules', 'Modules')}}
            {{Form::select('modules', $modules, null, array('multiple' => 'multiple', 'name' => 'modules[]'))}}
        </div> -->

        <div class="form-group">
        <select multiple="multiple" name="modules[]" id="modules">
        @foreach($modules as $module)
            <option value="{{$module->id}}" >{{$module->name}}</option>
        @endforeach
        </select>
        </div>

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection