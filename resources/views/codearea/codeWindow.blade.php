<div id="ideCodeWindow">
    <textarea id="codeWindow" class="code">{{$initial_editor_code}}</textarea>
</div>

@section("scripts-end")
    @parent
    <script type="text/javascript">
        makeCodeMirror("codeWindow");

        // Can be used in the codeeditor.js file to change behaviour according to which code exists
        var hasPreCode = (document.getElementById("idePreCode") != null);
        if(hasPreCode){
            makeCodeMirror("pre_code");
        }

        var hasTestCode = (document.getElementById("ideTestCode") != null);
        if(hasTestCode){
            makeCodeMirror("test_code");
        }

        setRunButtonEvent("btnRunCode");
    </script>
@endsection
