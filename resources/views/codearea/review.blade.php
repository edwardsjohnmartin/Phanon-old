@extends("layouts.app")
<?php
    $lessons = $module->lessons();
?>
@section('content')
<a href="{{url("flow",["id" => $module->concept->course_id])}}">Return</a>
<div id="exercisePanel">
    <label for="ddlFilter">Filter</label>
    <select id="ddlFilter" onchange="filterExercises(this)">
        <option value="-1">All</option>
        @foreach($lessons as $lesson)
            <option value="{{$lesson->id}}">{{$lesson->name}}</option>
        @endforeach
    </select>
   @component("codearea/exerciseDash", ["lessons" => $lessons,"isReview"=>true])
    @endcomponent
</div>
<div id="codeIde">
    @component("codearea/codeEditorWithTests",["module"=>$module,"exercise"=>$exercise])
    @endcomponent
</div>
@endsection

@section('scripts')
<script>
    function filterExercises(ddl) {
        var lessonId = ddl.options[ddl.selectedIndex].value;
        if (lessonId > 0) {
            //$("#exerciseList li").hide();
            $("#exerciseList li").each(function (i,o) {
                var ob = $(o);
                var lessId = ob.data("lesson-id");
                if (lessId == lessonId)
                    ob.show();
                else
                    ob.hide();
            });
        } else {
            $("#exerciseList li").show();
        }
    }
</script>
@show