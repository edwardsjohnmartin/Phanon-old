@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "projectIde")

@php
    $initial_editor_code = $project->start_code;
    
    if(!empty($projectProgress)){
        $initial_editor_code = $projectProgress->contents;
    }
@endphp

@section('content')
    <div id="codeIde" class="fullIDE">
        @section("navButtons")
            <a class="flow" href="{{url('flow/' . $project->module->concept->course_id)}}">Course Flow</a>
        @endsection  

        @component('codearea.projectDetails', [
            'project' => $project,
            'team' => $team,
            'projectSurveyResponse' => $projectSurveyResponse
        ])
        @endcomponent

        @component('codearea.codeEditor', [
            'role' => $role,
            'item' => $project,
            'item_type' => 'project',
            'initial_editor_code' => $initial_editor_code
        ])
        @endcomponent
    </div>
@endsection
