@php
             if(isset($exercise)){
                 //why do I have to treat the exercise like a collection?
                 $exercise = $exercise[0];
             }else{
                 $exercise = new Exercise();
             }
@endphp

<h3>{{$exercise->name}}</h3>

<div id="codePrompt">{{$exercise->prompt}}</div>


<div id="pre_code_area">
    <label>Pre Code</label>
    <textarea id="pre_code" class="code">{{$exercise->pre_code}}</textarea>
</div>

@component("codearea/codeEditor",['startingcode' => $exercise->start_code])
@endcomponent


<div id="test_code_area">
    <label>Test Code</label>
    <textarea id="test_code" class="code">{{$exercise->test_code}}</textarea>
</div>

<div id="test_output_area">
    <label id="test_output">Test results go here.</label>
</div>

<!--These are not needed, they are only for making the pre and test codes look nice-->
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



{{-- test code for python needs this --}}
    @php
             echo '
<script type="text/x-python" id="test_code_to_run">';
             require('python/methods.py');
             echo '
</script>';
             @endphp
