<div id="ideMessages">
    <span id="alerts"></span>
    
    <div id="ideLogs">
        <h2>Log</h2>
        <ol id="ideLog"></ol>
    </div>

    @component("shared/popups",['slot'=>$slot])
    @endcomponent
</div>

@section("scripts-end")
@parent
<script>
    $("#ideLogs").click(function () {
        $("#ideLog").toggle();
    });
</script>
@endsection
