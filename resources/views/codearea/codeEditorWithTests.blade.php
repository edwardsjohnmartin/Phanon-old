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

{{-- test code for python needs this --}}
@section('scripts')
    @parent
    @component('scriptbundles/python-tests')
    @endcomponent
@endsection


@component("codearea/codeEditorWithPreCode",
            ['prompt' => $exercise->prompt,
            'pre_code' => $exercise->pre_code,
            'startingcode' => $exercise->start_code,
            'isEditor' => $isEditor])
{{-- must include test output here so that it will be loaded into the correc
        spot in the editor --}}
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

@can("exercise.autocomplete")
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
@endcan

<script type="text/javascript">
    function save(completed){
        var editor = getEditor('#ideCodeWindow',"");
        var contents = editor.getValue();
        var exercise_id = {{$exercise->id}};        

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{url("/save")}}',
            data: { contents: contents, exercise_id: exercise_id, completed: completed},
            success: function( ret ) {
                console.log(ret);
            }
        });
    }
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




