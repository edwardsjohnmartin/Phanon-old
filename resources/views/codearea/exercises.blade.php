@extends("layouts.app")

@section('content')
<div id="exercisePanel">
   @component("codearea/exerciseDash", ["exercises" => $lesson->exercises()])
    @endcomponent
</div>
<div id="codeIde">
    @component("codearea/codeEditor")
    @endcomponent
</div>
@endsection