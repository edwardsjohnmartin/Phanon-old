<div id="idePreCode">
    <label for="pre_code">Pre Code</label>
    <textarea id="pre_code" class="code">{{$pre_code}}</textarea>
</div>

@section("scripts-end")
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            // Make pre code div hidden
            // This had to be done here because just giving the div the hidden class on the html
            // was causing a bug where the codeMirror text wasn't showing correctly
            $("#idePreCode").addClass("hidden");

            // Set height of pre code codeMirror
            var editor = $('#idePreCode').find('.CodeMirror')[0].CodeMirror;
            editor.setSize(null, 100);
        });
    </script>
@endsection
