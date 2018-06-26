<?php
use App\Exercise;
use App\ExerciseProgress;
use App\Lesson;
use App\Module;

$editorCode = "code for editor window will go here.";
//$editorState = -1; // no code;
$previousAttempt = new ExerciseProgress();
if(!isset($isEditor)) $isEditor = false;

if(!isset($exercise)){
    // create a new exercise so the page does not blow up.
    // HACK: probably need to redirect instead. It will make more sense.
    $exercise = new Exercise();
    $exercise->lesson = new Lesson();
    $exercise->lesson->module = $module;
    $previousAttempt->last_contents = null;
    $previousAttempt->last_correct_contents = null;
}else{
    //why do I have to treat the exercise like a collection?
    //$exercise = $exercise[0];
    $previousAttempt = ExerciseProgress::where('user_id',auth()->user()->id)->where('exercise_id',$exercise->id)->first();
    if(empty($previousAttempt)){
        // no previous attempt.
        $previousAttempt = new ExerciseProgress();
        $previousAttempt->last_contents = null;
        $previousAttempt->last_correct_contents = null;
    }
    //if(!empty($previousAttempt)){
    //    // has previous attempt
    //    if(isset($previousAttempt->last_correct_contents)){
    //        $editorCode = $previousAttempt->last_correct_contents;
    //        $editorState = 2; // last correct code;
    //    }else{
    //        $editorCode = $previousAttempt->last_contents;
    //        $editorState = 1; // last run code/ not correct
    //    }
    //}else{
    //    // no previous attempt
    //    $editorCode = $exercise->start_code;
    //    $editorState = 0; // starter code

    //}
}
?>

{{-- test code for python needs this --}}
@section('scripts')
    @parent
    @component('scriptbundles/python-tests')
    @endcomponent
@endsection

@component("codearea/codeEditorWithPreCode",
            ['prompt' => $exercise->prompt,
            'pre_code' => $exercise->pre_code,
            'lastruncode' => $previousAttempt->last_contents,
            'lastsolution' => $previousAttempt->last_correct_contents,
            'startingcode' => $exercise->start_code,
            'isEditor' => $isEditor,
            'editor_type' => "exercise",
            'save_id' => $exercise->id,
            'save_url' => url('/save')])

{{-- 
// moved to base editor
@if($editorState == 0)
<h3>Starter Code</h3>
@elseif($editorState == 1)
<h3>Last run Code</h3>
@elseif($editorState == 2)
<h3>Last Solution Code</h3>
@else
<h3>No Code</h3>
@endif
 --}}

{{-- must include test output here so that it will be loaded into the correc
        spot in the editor --}}
<div id="ideTestOutput" class="ideMessages">
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

@can("exercise.autocomplete")
    @if(!empty($users))
    {!! Form::open(['id' => 'exerciseComplete', 'action' => ['ExerciseProgressController@complete', $exercise->id], 'method' => 'PUT']) !!}
<div class="form-group">
    <label>Select which student you want to complete the exercise for</label>
    <select id="user" name="user" class="form-control">
        @foreach($users as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
    </select>
</div>



        {{Form::submit('Complete Exercise', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endif
@endcan

<script type="text/javascript">
    // HACK: Create variables to store the information to save an exercise.
    var global_exercise_id = {{$exercise->id}};
    var global_save_url = '{{url("/save")}}';
</script>

@section('scripts-end')
@parent
<!--These are not needed, they are only for making the pre and test codes look nice-->
@if($isEditor)
<script>
    CodeMirror.fromTextArea(document.getElementById("test_code"), {
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true
    });
</script>
@endif
@endsection




