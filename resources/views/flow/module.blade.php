<?php
    // Check whether this module was just created through an AJAX call to set visibility of create buttons
    if(!isset($ajaxCreation)){
        $ajaxCreation = false;
    }

    $startdate = $module->open_date;
    $now = date(config("app.dateformat"));

    $lessonsAndProjectsCount = 1;
    if($lessonsAndProjectsCount > 0){
        $stats = $module->CompletionStats(auth()->user()->id);
        
        if(!is_null($stats)){
            $is_completed = ($stats->Completed == $stats->ExerciseCount);
            $stats_perc_complete = floor($stats->PercComplete*100);
            $stats_completed = $stats->Completed;
            $stats_exercise_count = $stats->ExerciseCount;
        } else {
            $is_completed = false;
            $stats_perc_complete = 0;
            $stats_completed = 0;
            $stats_exercise_count = 0;
        }    
    } else {
        $is_completed = false;
        $stats = null;
        $stats_perc_complete = 0;
        $stats_completed = 0;
        $stats_exercise_count = 0;
    }
    $moduleOpen = !$is_completed;
?>

<article data-module-id="{{$module->id}}" class="module sortable{{$is_completed  ? ' expired' : ' current' }}">
    @if($role->hasPermissionTo(Permissions::MODULE_EDIT))
        <div class="dragHandleModule">Move Me</div>
    @endif

   <div class="completion tiny p{{$stats_perc_complete}}">
        <span>
            @if(!is_null($stats))
                @if($stats_completed < $stats_exercise_count)
                    {{$stats_completed}}/{{$stats_exercise_count}}
                @else
                    Done
                @endif
            @endif
        </span>

        <div class="slice">
            <div class="bar"></div>
            <div class="fill"></div>
        </div>
    </div>

    <h1>
        <a class="namespan" href="{{url('/code/module/' . $module->id)}}">{{$module->name}}</a>
        <input class="hidden nameinput" type="text" value="{{$module->name}}"/>

        <span>({{$stats_completed}} / {{$stats_exercise_count}})</span>
    </h1>
    @if($role->hasPermissionTo(Permissions::MODULE_EDIT))
    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="copy" href="{{url('/modules/' . $module->id . '/copy')}}">Copy</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>
@endif
    <div class="dates">
        <span class="start datespan">{{$module->getOpenDate(config("app.dateformat_short"))}}</span>
        <input class="hidden dateinput" type="datetime-local" value="{{$module->getOpenDate('Y-m-d\TH:i:s')}}"/>
    </div>

    <ul class="components">
        @if(count($module->components) > 0)
            @foreach($module->components as $comp)
                @if(get_class($comp) == "App\Lesson")
                    @component('flow.lesson', [
                        'lesson' => $comp,
                        'role'=>$role
                    ])
                    @endcomponent
        
                @else
                    @component('flow.project', [
                        'project' => $comp,
                        'role'=>$role
                    ])
                    @endcomponent
                @endif
            @endforeach
        @endif
    </ul>

    <div class="creation {{$ajaxCreation ? '' : 'hidden'}}">
        <button class="lesson add" onclick="createLesson(this, {{$module->id}}, '{{url('/ajax/lessoncreate')}}')">Add New Lesson</button>
        <button class="project add" onclick="createProject(this, {{$module->id}}, '{{url('/ajax/projectcreate')}}')">Add New Project</button>
    </div>

    <button class="contentControl {{$moduleOpen ? "collapser":"expander"}}">
        Show/Hide Contents
    </button>
</article>

{{-- events for contentControl is handled at the index page level. --}}
