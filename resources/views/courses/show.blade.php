@extends('layouts.app')

@section('content')
    <a href="{{url('/courses')}}" class="btn btn-default">Go Back</a>
    <h1>{{$course->name}}</h1>
    <div>
        <label>Open Date</label>
        <p>{{$course->getOpenDate()}}</p>
    </div>
    <div>
        <label>Close Date</label>
        <p>{{$course->getCloseDate()}}</p>
    </div>
    <div>
        <label>Concepts</label>
        @if(!empty($course->concepts()))
            <ul class="list-group">
            @foreach($course->concepts() as $concept)
                <li class="list-group-item"><a href="{{url('/concepts/' . $concept->id)}}">{{$concept->name}}</a></li>
            @endforeach
            </ul>
        @else
            <p>This course does not contain any concepts</p>
        @endif
    </div>
    <div>
        @php
        //HACK: This should not be here but I don't know how else to get access to the class
        use Spatie\Permission\Models\Role;
        @endphp
        <label>Course Participants</label>
        @if(count($course->users) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($course->users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{Role::find($user->pivot->role_id)->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>This course does not contain any participants</p>
        @endif
    </div>
    <div>
        <small>Author: {{$course->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$course->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$course->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $course->user_id)
            <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection