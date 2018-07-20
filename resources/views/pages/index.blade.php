@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>Welcome To Phanon!</h1>
        <p>The Virtuoso Programmer</p>
        @if(auth()->guest())
            <p><a class="btn btn-primary btn-lg" href="{{url('/login')}}" role="button">Login</a> <a class="btn btn-success btn-lg" href="{{url('/register')}}" role="button">Register</a></p>
        @else
        <p>
            <a class="btn btn-primary btn-lg" href="{{url('/dashboard')}}">Go to your DashBoard</a>
        </p>
        @endif
        <p>Phanon is an online Integrated Coding Environment. By doing small iterative programming problems, 
        users can learn programming concepts and skills. The principle of Phanon is small repetetive exercises to help
        people learn programming in a more memorable way. You can learn more by clicking on the <a href="{{url('about')}}">About page</a>.</p>
    </div>
@endsection