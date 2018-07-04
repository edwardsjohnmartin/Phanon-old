@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "projectIDE")

@php
    // Use the users latest attempt's code or the project's start_code if the user has never attempted it yet
    $latest_user_code = $project->start_code;

    if(!empty($projectProgress)){
        $latest_user_code = $projectProgress->contents;
    }
@endphp

@section('content')
    <div id="codeIde" class="fullIDE">
        @component('codearea.prompt', ['prompt' => $project->prompt])
        @endcomponent

        @component('codearea.precode', ['pre_code' => $project->pre_code])
        @endcomponent

        @component('codearea.codeEditor', ['start_code' => $project->start_code, 'latest_user_code' => $latest_user_code, 'item_type' => 'project', 'item_id' => $project->id])
        @endcomponent
    </div>
@endsection
