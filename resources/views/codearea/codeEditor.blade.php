@section('scripts')
@parent {{-- use to make sure that any scripts on parent pages are also included. --}}
@component("scriptbundles/sculpt")
@endcomponent
@component("scriptbundles/codemirror")
@endcomponent
@endsection

<div>
    <div id="ideControls">
        <button type="button" class="btn btn-default run" id="runButton">Run</button>
    </div>
    <textarea id="codeWindow" class="code"></textarea>
    <div id="mycanvas"></div>
    <pre id="output"></pre>
</div>

<script type="text/javascript">
    makeClassCodeMirror("#codeWindow").forEach(function (editorEl) {
        CodeMirror.fromTextArea(editorEl, {
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true
        });
    });
    makeRunButton('runButton');
</script>