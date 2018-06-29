@php
             if(!isset($startingcode)){
                 $startingcode = "Enter Code Here";
             }
             // set this to settings when not saving data.
             if(!isset($editor_type))
                 $editor_type = 'none';
             if(!isset($save_id))
                 $save_id = -1;
             if(!isset($save_url))
                 $save_url = "";
@endphp

@section('scripts')
    @parent {{-- use to make sure that any scripts on parent pages are also included. --}}
    @component("scriptbundles/skulpt")
    @endcomponent
    @component("scriptbundles/codemirror")
    @endcomponent
    @component("scriptbundles.codewindow")
    @endcomponent
@endsection
@section("bodyCSSClass")
activeIDE
@endsection

<div id="ideControls">
    <button type="button" class="btn btn-default run" id="btnRunCode"
        data-editor-type="{{$editor_type}}"
        data-save-id="{{$save_id}}"
        data-save-url="{{$save_url}}">
        Save & Run
    </button>
    {{-- <button type="button" class="btn btn-default save" id="btnSaveCode"
        data-editor-type="{{$editor_type}}"
        data-save-id="{{$save_id}}"
        data-save-url="{{$save_url}}">
        Save
    </button> --}}
    <button type="button" class="btn btn-default load" id="btnLoadCode"
        data-editor-type="{{$editor_type}}"
        data-save-id="{{$save_id}}"
        data-save-url="{{$save_url}}">
        Load
    </button>
       <button type="button" class="btn btn-default load" id="btnLoadSolution"
        data-editor-type="{{$editor_type}}"
        data-save-id="{{$save_id}}"
        data-save-url="{{$save_url}}">
        Load Solution
    </button>
       <button type="button" class="btn btn-default reset" id="btnReset"
        data-editor-type="{{$editor_type}}"
        data-save-id="{{$save_id}}"
        data-save-url="{{$save_url}}">
        Reset
    </button>
    <div id="ideMessages">
        <div id="ideMessageList">
            <div id="ideErrors" class="ideMessages">
                <label id="error_output">Python Error Messages Will Go Here</label>
                <div class="messageControls">
                    <a href="#" class="minimizer">_</a>
                    <a href="#" class="closer">X</a>
                </div>
            </div>
            {{$slot}}
        </div>
    </div>
</div>
<div id="ideMainEditor">
    <div id="ideCodeWindow">
        <textarea id="codeWindow" class="code">{{$startingcode}}</textarea>
    </div>
    <div id="ideOutputWindows">
        <div id="ideGraphics">
            <h3>Graphics</h3>
            <div id="mycanvas"></div>
        </div>
        <div id="ideTextOutput">
            <h3>Text Out</h3>
            <pre id="output"></pre>
        </div>
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

    //$("#btnTestMessages").click(function () {
    //    $("#ideTestOutput").addClass("showMessage");
    //    $("#ideErrors").addClass("showMessage");
    //});
    //$("#btnTestPythonMessages").click(function () {
    //    $("#ideErrors").addClass("showMessage");
    //});
    //$("#btnTestTestMessages").click(function () {
    //    $("#ideTestOutput").addClass("showMessage");
    //});

</script>