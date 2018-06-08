@extends('layouts.app')

@section('scripts')
@component("scriptbundles/sculpt")
@endcomponent
@component("scriptbundles/codemirror")
@endcomponent
@endsection

@section('content')
<h2>New Sandbox</h2>

<div id="error_output_area">
    <label id="error_output">Python Error Messages Will Go Here</label>
</div>
<div id="test_output_area">
    <label id="test_output">Test Error Messages Will Go Here</label>
</div>

@component("codearea/codeEditor")
@endcomponent

<div id="pre_code_area">
    <label>Pre Code</label>
    <textarea id="pre_code" class="code">def myFunc():
    print("pre_code_call")</textarea>
</div>

<div id="test_code_area">
    <label>Test Code</label>
    <textarea id="test_code" class="code">test_out("hello")</textarea>
</div>

<script>
    makeClassCodeMirror("#pre_code").forEach(function (editorEl) {
        CodeMirror.fromTextArea(editorEl, {
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true
        });
    });

    makeClassCodeMirror("#test_code").forEach(function (editorEl) {
        CodeMirror.fromTextArea(editorEl, {
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true
        });
    });
</script>
@php
{{-- test code for python needs this --}}
    echo '<script type="text/x-python" id="test_code_to_run">';
        require('python/methods.py'); 
    echo '</script>';
@endphp
@endsection