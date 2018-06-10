@extends('layouts.app')

@section('scripts')
@component("scriptbundles/sculpt")
@endcomponent
@component("scriptbundles/codemirror")
@endcomponent
<script>
    function fillCodeEditor(ddl) {
        var codeEditor = $("#ideCodeWindow .CodeMirror");
        var codeToUse = ddl.options[ddl.selectedIndex].value;
        codeEditor[0].CodeMirror.setValue(decodeURI(codeToUse));
    }
</script>
@endsection

@section('content')
<h2>New Sandbox</h2>
<label for="ddlExamples">Examples</label>
<select id="ddlExamples" onchange="fillCodeEditor(this)">
    <!-- %0D%0A = CRLF
         %09 = TAB        -->
    <option value="">Clear</option>
    <option value='print("hello world")'>Hello World</option>
    <option value='import turtle%0D%0At = turtle.Turtle()%0D%0At.forward(200)'>Turtle</option>
    <option value='for ndx in range(10):%0D%0A%09print(ndx)'>Loop</option>
    <option value='
import turtle
t = turtle.Turtle()
for ndx in range(15):
    t.forward(10*ndx)
    t.right(60)'>
        Looping Turtle
    </option>
        <option value='
import turtle
t = turtle.Turtle()
t.shape("turtle")
for ndx in range(30):
    t.forward(10*ndx)
    t.right(ndx*10)'>
        Crazy Turtle
    </option>
</select>
@component("codearea/codeEditor",["startingcode"=>""])
@endcomponent

@endsection