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

                    @can('course.create')
                        <a href="{{url('/flow/course/create')}}" class="btn btn-primary btn-add">Create Course</a>
                    @endcan

                    <h3>Your Courses</h3>
                    <ul class="courseList">
                        @foreach($courses as $course)
                            @component('pages.dashCourse',['course' => $course])
                            @endcomponent
                            @component('pages.dashCourse',['course' => $course])
                            @endcomponent
                            @component('pages.dashCourse',['course' => $course])
                            @endcomponent
                            @component('pages.dashCourse',['course' => $course])
                            @endcomponent
                        @endforeach
                    </ul>
                    {{--
                    #region table courses
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
                                <a href="{{url('/flow/' . $course->id)}}" class="btn btn-view flow">See Flow</a>
                                @can(Permissions::COURSE_EDIT)
                                <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
                                @endcan
                                @can(Permissions::COURSE_DELETE)
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
                    #endregion
                    --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection