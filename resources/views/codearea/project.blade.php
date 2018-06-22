@extends("layouts.app")
@section('content')
<div id="codeIde" class="fullIDE projectIDE">
    @component("codearea/codeEditorWithPreCode",[
            'prompt' => $project->prompt,
            'pre_code' => $project->pre_code,
            'startingcode' => $project->start_code,
            'isEditor' => false])
    @endcomponent
</div>
@endsection