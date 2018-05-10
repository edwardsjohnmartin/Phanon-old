@extends('layouts.app')

@section('content')
    <a href="{{url('/modules/create')}}" class="btn btn-primary">Create Module</a>
    <h1>Modules</h1>
    @if(count($modules) > 0)
        @foreach($modules as $module)
            <div class="well">
                <h3><a href="{{url('/modules/' . $module->id)}}">{{$module->name}}</a></h3>
                <small>Created on {{$module->created_at}}</small>
            </div>
        @endforeach
        {{$modules->links()}}
    @else
        <p>No modules found</p>
    @endif
@endsection