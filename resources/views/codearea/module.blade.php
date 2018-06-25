@extends("layouts.app")

@section('content')
<div id="exercisePanel">
    @component("codearea/exerciseDash", ["lessons" => $module->lessons(), 'module_completion' => $module_completion])
    @endcomponent
</div>
<div id="codeIde">
    @component("codearea/codeEditorWithTests",['exercise'=>$exercise])
    @endcomponent
</div>
@endsection