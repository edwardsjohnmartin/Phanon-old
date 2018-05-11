@extends('layouts.app')

@section('content')

    <h1 class="text-center">Sandbox</h1>

    <div>
        <button type="button" class="btn btn-default" id="runButton">Run</button>
        <textarea id="code" class="code"></textarea>
        <div id="mycanvas"></div>
        <pre id="output"></pre>
    </div>

    <script type="text/javascript">
        makeClassCodeMirror(".code").forEach(function (editorEl){
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
@endsection