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
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection