@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "projectIde")

@php
    $initial_editor_code = $project->start_code;
    
    if(!empty($projectProgress)){
        $initial_editor_code = $projectProgress->contents;
    }
    print_r( auth()->user()->teamForProject($project->id));
@endphp

@section('content')
    <div id="codeIde" class="fullIDE">
        @section("navButtons")
            <a class="flow" href="{{url('flow/' . $project->module->concept->course_id)}}">Course Flow</a>
        @endsection  

        @component('codearea.prompt', ['prompt' => $project->prompt, 'show_survey' => true,
                    'team' => auth()->user()->teamForProject($project->id)])
        @endcomponent

        @component('codearea.precode', ['pre_code' => $project->pre_code])
        @endcomponent

        @component('codearea.codeEditor', [
            'item' => $project,
            'item_type' => 'project',
            'initial_editor_code' => $initial_editor_code
        ])
        @endcomponent
    </div>
@endsection
