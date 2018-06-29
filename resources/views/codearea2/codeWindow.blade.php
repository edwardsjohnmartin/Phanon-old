
<div id="ideCodeWindow">
    <textarea id="codeWindow" class="code">{{$start_code}}</textarea>
</div>
@section("scripts-end")
<script type="text/javascript">
    @parent
    makeCodeMirror("codeWindow");
    makeCodeMirror("pre_code");
    makeCodeMirror("test_code");

    makeRunButton("btnRunCode");
</script>
@endsection