<div id="courseDetails" data-course-url="{{url('/ajax/courseedit')}}" data-course-id="{{$course->id}}">
    <h1 id="courseName" class="editable">{{$course->name}}</h1>
    <aside class="dates">
        <span id="courseOpenDate" class="start datepicker editable">{{$course->getOpenDate(config('app.dateformat_short'))}}</span>
        <span> - </span>
        <span id="courseCloseDate" class="end datepicker editable">{{$course->getCloseDate(config('app.dateformat_short'))}}</span>
    </aside>
    
    <aside class="actions">
        <a href="{{url('/courses/' . $course->id)}}" class="view">View</a>

        @can(Permissions::COURSE_EDIT)
            <a href="{{url('/courses/' . $course->id . '/edit')}}" class="edit">Edit</a>
        @endcan

        @can(Permissions::COURSE_DELETE)
            <a href="{{url('/courses/' . $course->id . '/delete')}}"
                onclick="return actionVerify(event,'{{'delete '.$course->name}}');" class="delete">
                Delete
            </a>
        @endcan
        
        @can(Permissions::TEAM_CREATE)
            <a href="{{url('/courses/' . $course->id . '/teams')}}" class="teams">View Teams</a>
        @endcan

        @if($role->hasPermissionTo(Permissions::COURSE_EDIT))
            <a href="{{url('/courses/' . $course->id . '/participants')}}" class="teams">View Participants</a>
        @endif
    </aside>

    <aside id="courseEditButtons">
        @if($role->hasPermissionTo(Permissions::COURSE_EDIT))
            <button class="edit" onclick="toggleEditMode(this);" tooltip="ToggleEditMode">Enable Edit Mode</button>
        @endif
        <button class="expandAll" onclick="expandModules();" tooltip="Expand All Modules">Expand</button>
        <button class="collapseAll" onclick="collapseModules();" tooltip="Collapse All Modules">Collapse</button>
    </aside>

    @component("shared/popups")
    @endcomponent
</div>

<section id="courseContent" data-module-move-url="{{url("modules/move/")}}" data-component-move-url="{{url("lessons/move/")}}"  >
    @if(count($course->concepts) > 0)
        @foreach($course->concepts as $concept)
            @component('flow.concept', [
                'concept' => $concept,
                'role'=>$role
            ])
        @endcomponent
        @endforeach
    @else
        <div class="placeholder">No Concepts found</div>
    @endif
</section>

<div class="creation hidden">
    <button class="concept add" onclick="createConcept({{$course->id}}, '{{url('/ajax/conceptcreate')}}')">Add New Concept</button>
</div>
    