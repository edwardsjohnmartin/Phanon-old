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
             // make sure whether or not we want the editor is set
             // this will make sure that we only show what we need to.
             if(!isset($isEditor)){
                 $isEditor = false;
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
@if($isEditor)
{{-- do not show the editor if not editiing the exercise/project --}}
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
@endif
@endsection

<div id="idePrompt">{{$exercise->prompt}}</div>

{{-- Show precode if editing--}}
@if($isEditor)
<div id="idePreCode">
    <label>Pre Code</label>
    <textarea id="pre_code" class="code">{{$exercise->pre_code}}</textarea>
</div>
@else
<input type="hidden" id="pre_code" value='{{$exercise->pre_code}}' />
@endif

@component("codearea/codeEditor",['startingcode' => $exercise->start_code])
<div id="ideTestOutput">
    <label id="test_output">Test Results go here.</label>
    <div class="messageControls">
        <a href="#" class="minimizer">_</a>
        <a href="#" class="closer">X</a>
    </div>
</div>
@endcomponent


{{-- Show test code if editing--}}
@if($isEditor)
<div id="ideTestCode">
    <label>Test Code</label>
    <textarea id="test_code" class="code">{{$exercise->test_code}}</textarea>
</div>
@else
<input type="hidden" id="test_code" value='{{$exercise->test_code}}' />
@endif





