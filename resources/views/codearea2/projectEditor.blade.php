@extends('layouts.app')

{{-- does not add new lines to css --}}
@section("bodyCSSClass", "projectIDE")

@section('content')
    <div id="codeIde" class="fullIDE">
        @component('codearea2.prompt', ['prompt' => $project->prompt])
        @endcomponent

        @component('codearea2.precode', ['pre_code' => $project->pre_code])
        @endcomponent

        @component('codearea2.codeEditor', ['start_code' => $project->start_code, 'item_type' => 'project', 'item_id' => $project->id])
        @endcomponent
    </div>
@endsection
