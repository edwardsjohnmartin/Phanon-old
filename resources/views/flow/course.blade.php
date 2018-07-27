<div id="courseDetails" data-course-url="{{url('/ajax/courseedit')}}" data-course-id="{{$course->id}}">
    <h1 id="courseName" class="editable">{{$course->name}}</h1>
    <aside class="dates">
        <!--TODO: these dates should come preformatted-->
        <!--Not sure why we are parsing them then reformatting them again.-->
        <span id="courseOpenDate" class="start editable">{{$course->getOpenDate(config('app.dateformat_short'))}}</span>
        <span> - </span>
        <span id="courseCloseDate" class="end editable">{{$course->getCloseDate(config('app.dateformat_short'))}}</span>
    </aside>
    
    <aside class="actions">
        <a href="{{url('/courses/' . $course->id)}}" class="btn btn-view">View</a>

        @can(Permissions::COURSE_EDIT)
            <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-edit">Edit</a>
        @endcan

        @can(Permissions::COURSE_DELETE)
            <a href="{{url('/courses/' . $course->id . '/delete')}}"
                onclick="return actionVerify(event,'{{'delete '.$course->name}}');" class="btn btn-delete">
                Delete
            </a>
        @endcan
        
        @can(Permissions::TEAM_CREATE)
            <a href="{{url('/courses/' . $course->id . '/teams')}}" class="btn">View Teams</a>
        @endcan
    </aside>

    @if($role->hasPermissionTo(Permissions::COURSE_EDIT))
        <button class="pull-right" onclick="toggleEditMode(this);">Enable Edit Mode</button>
    @endif
</div>

@foreach($course->concepts() as $concept)
    @component("flow.concept",["concept" => $concept])
    @endcomponent
@endforeach

{{-- extends('layouts.app')

@section('content')
<a href="{{url('/courses')}}" class="btn btn-default">Go Back</a>
<a href="{{url('/courses/' . $course->id . '/fullview')}}" class="btn btn-default">Full View</a>
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
    @if(count($course->concepts()) > 0)
    <ul class="list-group">
        @foreach($course->concepts() as $concept)
        <li class="list-group-item">
            <a href="{{url('/concepts/'.$concept->id)}}">{{$concept->name}}</a>
            @foreach($concept->modules() as $module)
                @component('modules/partial',['module' => $module])
                                @endcomponent
            @endforeach
        </li>
        @endforeach
    </ul>
    @else
    <p>This course does not contain any concepts</p>
    @endif
</div>
<div>
    <small>Author: {{$course->owner->name}}</small>
</div>
<div>
    <small>Created On: {{$course->created_at}}</small>
</div>
<div>
    <small>Last Updated At: {{$course->updated_at}}</small>
</div>
<hr />
@if(!Auth::guest())
@if(Auth::user()->id == $course->owner_id)
<a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-default">Edit</a>

{!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection --}}
