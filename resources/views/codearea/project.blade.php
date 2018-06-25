@extends("layouts.app")
@section('content')
<a href="{{url("flow",["id" => $project->module->concept->course_id])}}">Return</a>
<div id="codeIde" class="fullIDE projectIDE">
    @component("codearea/codeEditorWithPreCode",[
            'prompt' => $project->prompt,
            'pre_code' => $project->pre_code,
            'startingcode' => $project->start_code,
            'isEditor' => false,
            'editor_type' => "project",
            'save_id' => $project->id,
            'save_url' => url('/save')])
    @endcomponent
</div>
@endsection