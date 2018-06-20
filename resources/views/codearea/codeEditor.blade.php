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
</div>
<div id="ideErrors">
    <label id="error_output">Python Error Messages Will Go Here</label>
</div>
{{$slot}}
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
</script>