@extends("layouts.app")

@section('content')
<div id="exercisePanel">
   @component("codearea/exerciseDash", ["lessons" => $module->lessons()])
    @endcomponent
</div>
<div id="codeIde">
    @component("codearea/codeEditor")
    @endcomponent
</div>
@endsection