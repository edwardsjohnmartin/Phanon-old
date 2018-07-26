<div id="ideSandboxExamples">
    <label for="ddlExamples">Examples</label>
    <select id="ddlExamples" onchange="handleExamplesDropDown(this)">
        <option value="">Clear</option>
        <option value='print("hello world")'>Hello World</option>
        <option value='import turtle%0D%0At = turtle.Turtle()%0D%0At.forward(200)'>Turtle</option>
        <option value='for ndx in range(10):%0D%0A%09print(ndx)'>Loop</option>
        <option value='import turtle%0At = turtle.Turtle()%0Afor ndx in range(15):%0A%09t.forward(10 * ndx)%0A%09t.right(60)'>Looping Turtle</option>
        <option value='import turtle%0At = turtle.Turtle()%0At.shape("turtle")%0Afor ndx in range(30):%0A%09t.forward(10*ndx)%0A%09t.right(ndx*10)'>Crazy Turtle</option>
    </select>
</div>

@section("scripts")
@parent
<script>
    function handleExamplesDropDown(own) {
        //alert(own.value);
        $("#btnReset").attr("data-reset-code", decodeURI(own.value));
        replaceEditorText('ideCodeWindow', own.value);
    }
</script>
@endsection