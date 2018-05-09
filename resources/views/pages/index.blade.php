@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>Welcome To Phanon!</h1>
        <p>The Virtuoso Programmer</p>
        @if(auth()->guest())
            <p><a class="btn btn-primary btn-lg" href="/login" role="button">Login</a> <a class="btn btn-success btn-lg" href="/register" role="button">Register</a></p>
        @endif
    </div>
@endsection