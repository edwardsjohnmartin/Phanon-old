@php
    //$stats = $concept->CompletionStats(auth()->user()->id);
@endphp

<article class="concept sortableConcept">

    {{--
    <div class="completion tiny p{{floor($stats->PercComplete*100)}}">
        <span>
            {{$stats->Completed}}/{{$stats->ExerciseCount}}
        </span>

        <div class="slice">
            <div class="bar"></div>
            <div class="fill"></div>
        </div>
    </div>--}}

    <h3>
        {{$concept->name}}
        {{-- <span>({{$stats->Completed}}/{{$stats->ExerciseCount}})</span> --}}
    </h3>

    @foreach($concept->modules() as $module)
        @component('flow.module',['module' => $module])
        @endcomponent
    @endforeach

    <div class="row edit-button-div" style="visibility: hidden; display: none;">
        <button class="center-block" onclick="createModule(this, {{$concept->id}})">Create New Module</button>
    </div>
</article>
