@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
@endcomponent
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{url('/courses/create')}}" class="btn btn-primary">Create Course</a>
                    <h3>Courses You've Created</h3>
                    @if(count($courses) > 0)
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->name}}</td>
                                    <td>
                                        <a href="{{url('/courses/' . $course->id . '/view')}}" class="btn btn-view">View</a>
                                        <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
                                        <a href="{{url('/courses/' . $course->id . '/delete')}}" onclick="return actionVerify(event,'{{'delete '.$course->name}}');" class="btn btn-delete">Delete</a>



                                    {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                                        {{Form::hidden('_method', 'DELETE')}}
                                        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                    {!!Form::close() !!}
<td>
                                <tr>
                            @endforeach
                    @else
                        <p>You have not created any courses</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection