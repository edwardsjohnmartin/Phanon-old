@php
             // make sure whether or not we want the editor is set
             // this will make sure that we only show what we need to.
             if(!isset($isEditor)){
                 $isEditor = false;
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
            ['startingcode' => $startingcode])
    {{-- this will pass down any message areas to the lower IDE --}}
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





