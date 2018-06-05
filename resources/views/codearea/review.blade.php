@extends("layouts.app")

@section('content')
<div id="exercisePanel">
    <label for="ddlFilter">Filter</label>
    <select id="ddlFilter">
        <option>Hello</option>
        <option>GoodBye</option>
        <option>C-Ya</option>
    </select>
   @component("codearea/exerciseDash", ["lessons" => $module->lessons()])
    @endcomponent
</div>
<div id="codeIde">
    @component("codearea/codeEditor")
    @endcomponent
</div>
@endsection