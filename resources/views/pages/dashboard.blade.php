@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent
@endsection
@php
    //HACK: This should not be here but I don't know how else to get access to the class
    use Spatie\Permission\Models\Role;
@endphp
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
                    @can('course.create')
                    <a href="{{url('/courses/create')}}" class="btn btn-primary btn-add">Create Course</a>
                    @endcan
                    <h3>Your Courses</h3>
                    @if(count($courses) > 0)
                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{$course->name}}</td>
                            @if($course->pivot)
                                <td>{{Role::find($course->pivot->role_id)->name}}</td>
                            @else
                                <td>Role goes here</td>
                            @endif
                            <td>{{$course->getCloseDate(config('app.dateformat_short'))}}</td>
                            <td>
                                <!-- HACK: Setting up structure for buttons -->
                                <a href="{{url('/courses/' . $course->id)}}" class="btn btn-view">View</a>
                                <a href="{{url('/flow/' . $course->id)}}" class="btn btn-view">See Flow</a>
                                @can('course.edit')
                                    <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
                                @endcan
                                @can('course.delete')
                                <a href="{{url('/courses/' . $course->id . '/delete')}}"
                                    onclick="return actionVerify(event,'{{'delete '.$course->name}}');"
                                    class="btn btn-delete">
                                    Delete
                                </a>
                                {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                                    {{Form::hidden('_method', 'DELETE')}}
                                    {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                                {!!Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">
                                You do not have any courses
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection