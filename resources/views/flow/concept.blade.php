@php
    //$stats = $concept->CompletionStats(auth()->user()->id);

    // Check whether this concept was just created through an AJAX call to set visibility of create button
    if(!isset($ajaxCreation)){
        $ajaxCreation = false;
    }
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

    <h3 class="editable">
        {{$concept->name}}
        {{-- <span>({{$stats->Completed}}/{{$stats->ExerciseCount}})</span> --}}
    </h3>
    <?php $concept->eagerLoading = $eagered; ?>
    @foreach($concept->modules() as $module)
        @component('flow.module',['module' => $module, 'eagered' => $eagered])
        @endcomponent
    @endforeach
    
    <div class="row create-button-div @if(!$ajaxCreation) hidden @endif">
        <button class="center-block" onclick="createModule(this, {{$concept->id}}, '{{url('/ajax/modulecreate')}}')">Create New Module</button>
    </div>
</article>
