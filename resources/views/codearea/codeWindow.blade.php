@if(isset($role) && ($role->hasPermissionTo(Permissions::PROJECT_EDIT) or $role->hasPermissionTo(Permissions::EXERCISE_EDIT)))
    <div id="ideCodeWindow">
        <ul class="nav nav-tabs">
            <li class="nav-item active"><a data-toggle="tab" href="#code">Code</a></li>
            <li class="nav-item"><a data-toggle="tab" href="#startcode">Start Code</a></li>
            <li class="nav-item"><a data-toggle="tab" href="#solution">Solution</a></li>
        </ul>

        <div class="tab-content">
            <div id="code" class="tab-pane fade in active">
                <textarea id="codeWindow1" class="code">{{$initial_editor_code}}</textarea>
            </div>
            <div id="startcode" class="tab-pane fade active">
                <textarea id="codeWindow2" class="code">{{$item->start_code}}</textarea>
            </div>
            <div id="solution" class="tab-pane fade active">
                <textarea id="codeWindow3" class="code">{{$item->solution}}</textarea>
            </div>
        </div>
    </div>
@else
    <div id="ideCodeWindow">
        <textarea id="codeWindow" class="code">{{$initial_editor_code}}</textarea>
    </div>
@endif

@section("scripts-end")
    @parent
    <script type="text/javascript">
        @if(isset($role) and ($role->hasPermissionTo(Permissions::PROJECT_EDIT) or $role->hasPermissionTo(Permissions::EXERCISE_EDIT)))
            makeCodeMirror("codeWindow1");
            makeCodeMirror("codeWindow2");
            makeCodeMirror("codeWindow3");
        @else
            makeCodeMirror("codeWindow")
        @endif

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
