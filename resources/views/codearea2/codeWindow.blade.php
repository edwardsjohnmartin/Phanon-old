<div id="ideCodeWindow">
    <textarea id="codeWindow" class="code">{{$start_code}}</textarea>
</div>

<script type="text/javascript">
    makeCodeMirror("codeWindow");
    makeCodeMirror("pre_code");
    makeCodeMirror("test_code");

    makeRunButton("btnRunCode");
</script>