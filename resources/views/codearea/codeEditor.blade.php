@php
             if(!isset($startingcode)){
                 $startingcode = "Enter Code Here";
             }
             @endphp

@section('scripts')
    @parent {{-- use to make sure that any scripts on parent pages are also included. --}}
    @component("scriptbundles/sculpt")
    @endcomponent
    @component("scriptbundles/codemirror")
    @endcomponent
@endsection

<div id="ideControls">
    <button type="button" class="btn btn-default run" id="btnRunCode">Run</button>
    <button type="button" class="btn btn-default run" id="btnTestMessages">Show Messages</button>
    <button type="button" class="btn btn-default run" id="btnTestPythonMessages">Show Compiler Messages</button>
    <button type="button" class="btn btn-default run" id="btnTestTestMessages">Show Test Messages</button>
</div>
<div id="ideMessages">
    <div>
        <div id="ideErrors">
            <label id="error_output">Python Error Messages Will Go Here</label>
            <div class="messageControls">
                <a href="#" class="minimizer">_</a>
                <a href="#" class="closer">X</a>
            </div>
        </div>
        {{$slot}}
    </div>
</div>
<div id="ideMainEditor">
    <div id="ideCodeWindow">
        <textarea id="codeWindow" class="code">{{$startingcode}}</textarea>
    </div>
    <div id="ideGraphics">
        <div id="mycanvas"></div>
    </div>
    <div id="ideTextOutput">
        <pre id="output"></pre>
    </div>
</div>

<script type="text/javascript">
    // this is more efficient to remove the foreach loop.
    //makeClassCodeMirror("#codeWindow").forEach(function (editorEl) {
    CodeMirror.fromTextArea(document.getElementById("codeWindow"), {
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true
    });
    //});
    makeRunButton('btnRunCode');

    $("#ideMessages .closer").click(function () {
        $(this).parent().parent().removeClass("collapseMessage").removeClass("showMessage");
    });

        $("#ideMessages .minimizer").click(function () {
        $(this).parent().parent().toggleClass("collapseMessage");
    });

    $("#btnTestMessages").click(function () {
        $("#ideTestOutput").addClass("showMessage");
        $("#ideErrors").addClass("showMessage");
    });
    $("#btnTestPythonMessages").click(function () {
        $("#ideErrors").addClass("showMessage");
    });
    $("#btnTestTestMessages").click(function () {
        $("#ideTestOutput").addClass("showMessage");
    });

</script>