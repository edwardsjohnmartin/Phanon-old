@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "projectIDE")

@php
    $initial_editor_code = $project->start_code;
    
    if(!empty($projectProgress)){
        $initial_editor_code = $projectProgress->contents;
    }
@endphp

@section('content')
    <div id="codeIde" class="fullIDE">
        <a class="flow" href="{{url('flow/' . $project->module->concept->course_id)}}">Return</a>
        
        @component('codearea.prompt', ['prompt' => $project->prompt])
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
