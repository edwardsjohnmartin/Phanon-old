@extends('layouts.app')

@section('content')
    <h1>Create Module</h1>
    {!! Form::open(['action' => 'ModulesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('open_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('close_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection