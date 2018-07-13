@php
    $stats = $module->CompletionStats(auth()->user()->id);
    if(!is_null($stats)){
        $exerciseCount = $stats->ExerciseCount;
        $exercisesCompleted = $stats->Completed;
        $percentCompleted = $stats->PercComplete * 100;
    } else {
        $exerciseCount = 0;
        $exercisesCompleted = 0;
        $percentCompleted = 0;
    }
@endphp
<article class="module">
    <div class="completion tiny p{{$percentCompleted}}">
        <span>
            @if($percentCompleted == 100)
                Done
            @else 
                {{$exercisesCompleted}}/{{$exerciseCount}}
            @endif
        </span>

        <div class="slice">
            <div class="bar"></div>
            <div class="fill"></div>
        </div>
    </div>

    <h1>
        {{$module->name}}
        <span>({{$exercisesCompleted}}/{{$exerciseCount}})</span>
    </h1>

    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="copy" href="{{url('/modules/' . $module->id . '/copy')}}">Copy</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>

    <div class="dates">
        <span class="start">{{$module->getOpenDate(config("app.dateformat_short"))}}</span>
    </div>

    <ul id="module_list" class="components" data-module_id="{{$module->id}}">
        @foreach($module->components as $comp)
            @if($comp->type == "lesson")
                @component('flow.newLesson', ['lesson' => $comp])
                @endcomponent
            @else
                @component('flow.newProject', ['project' => $comp])
                @endcomponent
            @endif
        @endforeach
    </ul>

    <button class="contentControl collapser" onclick="toggleExpandCollapse(this, {{$module->id}})">Show/Hide Contents</button>
</article>
