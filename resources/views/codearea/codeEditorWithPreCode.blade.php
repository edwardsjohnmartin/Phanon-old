@php
             /// This page is for use when you only need pre-code like projects.
             //             No tests are ran by using just this file.
             // this Page is included when you need tests like exercises or test.


             // make sure whether or not we want the editor is set
             // this will make sure that we only show what we need to.
             $editorState = -1; // no code;
             if(!isset($isEditor)){
                 $isEditor = false;
             }
             if(!isset($startingcode)){
                 $startingcode = null;
             }else{
                 $editorState = 0; // starter code
             }
             if(!isset($lastruncode)){
                 $lastruncode = null;
             }else{
                 $startingcode = $lastruncode;
                 $editorState = 1; // last run code/ not correct
             }
             if(!isset($lastsolution)){
                 $lastsolution = null;
             }else{
                 $startingcode = $lastsolution;
                 $editorState = 2; // last correct code;
             }
@endphp
<div id="idePrompt">{!! $prompt !!}</div>


{{-- Show precode if editing--}}
@if($isEditor)
<div id="idePreCode">
    <label>Pre Code</label>
    <textarea id="pre_code" class="code">{{$pre_code}}</textarea>
</div>
@else
<input type="hidden" id="pre_code" value='{{$pre_code}}' />
@endif

@component("codearea/codeEditor",
            ['startingcode' => $startingcode,
            'editor_type' => $editor_type,
            'save_id' => $save_id,
            'save_url' => $save_url])

    {{-- this will pass down any message areas to the lower IDE --}}
<div id="ideAlerts" class="ideMessages showMessage">
    <label id="message_output">
        @if($editorState == 0)
            Starter code
        @elseif($editorState == 1)
            Your last run code
        @elseif($editorState == 2)
           Your last solution code
        @else
            No Code
        @endif
         has been loaded into the editor.
    </label>
    <div class="messageControls">
        <a href="#" class="minimizer">_</a>
        <a href="#" class="closer">X</a>
    </div>
</div>


{{$slot}}
@endcomponent

@section('scripts-end')
    @parent
<!--These are not needed, they are only for making the pre codes look nice-->
@if($isEditor)
            {{-- do not show the editor if not editiing the exercise/project --}}
<script>
    CodeMirror.fromTextArea(document.getElementById("pre_code"), {
        lineNumbers: true,
        cursorBlinkRate: 0,
        autoCloseBrackets: true,
        tabSize: 4,
        indentUnit: 4,
        matchBrackets: true,

    });
</script>
@endif
@endsection





