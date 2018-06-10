@php
    use App\Exercise;
    use App\Lesson;
    use App\Module;
             if(!isset($exercise)){
                 // create a new exercise so the page does not blow up.
                 // HACK: probably need to redirect instead. It will make more sense.
                 $exercise = new Exercise();
                 $exercise->lesson = new Lesson();
                 $exercise->lesson->module = $module;
             }else{
                 //why do I have to treat the exercise like a collection?
                 //$exercise = $exercise[0];
             }
@endphp

<h3>Exercises for Module {{$exercise->lesson->module->name}}</h3>
{{-- test code for python needs this --}}
@section('scripts')
@parent
    @component('scriptbundles/python-tests')
    @endcomponent
@endsection
@section('scripts-end')
@parent
<!--These are not needed, they are only for making the pre and test codes look nice-->
<script>
    //makeClassCodeMirror("#pre_code").forEach(function (editorEl) {
        CodeMirror.fromTextArea(document.getElementById("pre_code"), {
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true,
            
        });
    //});

    //makeClassCodeMirror("#test_code").forEach(function (editorEl) {
        CodeMirror.fromTextArea(document.getElementById("test_code"), {
            lineNumbers: true,
            cursorBlinkRate: 0,
            autoCloseBrackets: true,
            tabSize: 4,
            indentUnit: 4,
            matchBrackets: true
        });
    //});
</script>
@endsection

<div id="idePrompt">{{$exercise->prompt}}</div>


<div id="idePreCode">
    <label>Pre Code</label>
    <textarea id="pre_code" class="code">{{$exercise->pre_code}}</textarea>
</div>

@component("codearea/codeEditor",['startingcode' => $exercise->start_code])
@endcomponent


<div id="ideTestCode">
    <label>Test Code</label>
    <textarea id="test_code" class="code">{{$exercise->test_code}}</textarea>
</div>

<div id="ideTestOutput">
    <label id="test_output">Test results go here.</label>
</div>




