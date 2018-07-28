<div id="ideTestCode">
    <label for="test_code">Test Code</label>
    <textarea id="test_code" class="code">{{$test_code}}</textarea>
</div>

@section('scripts')
    @parent

    @component('scriptbundles.python-tests')
    @endcomponent
@endsection

@section("scripts-end")
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            // Make pre code div hidden
            // This had to be done here because just giving the div the hidden class on the html
            // was causing a bug where the codeMirror text wasn't showing correctly
            $("#ideTestCode").addClass("hidden");

            // Set height of test code codeMirror
            var editor = $('#ideTestCode').find('.CodeMirror')[0].CodeMirror;
            editor.setSize(null, 100);
        });
    </script>
@endsection