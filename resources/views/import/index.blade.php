@extends ("layouts.app")

@section("content")

<h1>Import</h1>
{{ Form::open(array('url' =>'import/upload','files'=>true)) }}
{{ Form::label('newLesson',"File",array('id'=>"",'class'=>"")) }}
{{ Form::file('newLesson',"",array('id'=>"",'class'=>"")) }}
{{ Form::submit("Upload") }}
{{ Form::close() }}
@endsection

@php
//phpinfo();
@endphp