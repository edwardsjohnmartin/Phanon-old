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
<?php $course->eagerLoading = $eagered; ?>
@foreach($course->concepts() as $concept)
    @component("flow.concept",["concept" => $concept, 'eagered' => $eagered])
    @endcomponent
@endforeach
