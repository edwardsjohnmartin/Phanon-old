
<li class="course">
    <a href="{{url('/flow/' . $course->id)}}">
        <h4>{{$course->name}}</h4>
    </a>
    <dl class="dates">
        <dt>Opens</dt>
        <dd>{{$course->getOpenDate(config('app.dateformat_short'))}}</dd>
        <dt>Closes</dt>
        <dd>{{$course->getCloseDate(config('app.dateformat_short'))}}</dd>
    </dl>
    <aside class="actions">
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
        @endcan
    </aside>
</li>


