@extends('layouts.app')

@section('content')
    <a href="{{url('/modules')}}" class="btn btn-default">Go Back</a>
    <h1>{{$module->name}}</h1>
    <h2>Open Date: {{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $module->open_date), 'm/d/Y h:i a')}}</h2>
    <h2>Close Date: {{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $module->close_date), 'm/d/Y h:i a')}}</h2>
    <hr>
    @if(!Auth::guest())
        <a href="{{url('/modules/' . $module->id . '/edit')}}" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['ModulesController@destroy', $module->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection