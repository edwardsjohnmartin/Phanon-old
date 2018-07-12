<?php
$startdate = $module->open_date;
$now = date(config("app.dateformat"));
//HACK: major hack this is not the way it should be done.
$lessonsAndProjectsCount = count($module->lessonsAndProjects());
if($lessonsAndProjectsCount > 0){
    $is_completed = $module->completed() <= 1; // See comments on this method.
    $stats = $module->CompletionStats(auth()->user()->id);
    if(!is_null($stats)){
        $stats_perc_complete = floor($stats->PercComplete*100);
        $stats_completed = $stats->Completed;
        $stats_exercise_count = $stats->ExerciseCount;
    } else {
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
<article 
   class="module sortable{{$is_completed  ? ' expired' : '' }}{{
            !$is_completed  ? ' current' : '' }}">
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
        {{-- #todo: need to fix this to acutally use the correct exercise --}}
        <a href="{{$is_completed 
            ? url('/code/review/'.$module->id.'/'.$module->id)
            : url('/code/'.$module->id.'/'.$module->id)}}">
            {{$module->name}}
        </a>
            <span>({{$stats_completed}} / {{$stats_exercise_count}})</span>
    </h1>
    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="copy" href="{{url('/modules/' . $module->id . '/copy')}}">Copy</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>
    <div class="dates">
        <!--TODO: these dates should come preformatted-->
        <!--Not sure why we are parsing them then reformatting them again.-->
        <span class="start">{{date_format($module->OpenDate(),config("app.dateformat_short"))}}</span>
    </div>
    {{--
    <ul class="lessons">
        @foreach($module->lessons() as $less)
            @component('flow.lesson',['lesson' => $less])
            @endcomponent
        @endforeach
    </ul>
    <ul class="projects">
        @foreach($module->projects() as $proj)
            @component('flow.project',['project' => $proj])
            @endcomponent
        @endforeach
    </ul>--}}

    <ul class="components">
        @if($lessonsAndProjectsCount > 0)
        @foreach($module->lessonsAndProjects() as $comp)
            @if(get_class($comp) == "App\Lesson")
                @component('flow.lesson',['lesson' => $comp])
                @endcomponent
            @else
                @component('flow.project',['project' => $comp])
                @endcomponent
            @endif
        @endforeach
        @endif
    </ul>
    <button class="contentControl {{$moduleOpen ? "collapser":"expander"}}"
            >Show/Hide Contents</button>
</article>

{{-- events for contentControl is handled at the index page level. --}}
