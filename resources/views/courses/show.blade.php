@extends('layouts.app')

@section('content')
    <a href="/courses" class="btn btn-default">Go Back</a>
    <h1>{{$course->name}}</h1>
    <small>Written on {{$course->created_at}}</small>
@endsection