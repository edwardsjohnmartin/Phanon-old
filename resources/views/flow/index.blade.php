@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent

    @component("scriptbundles/percentages")
    @endcomponent

    @component("scriptbundles/course-flow")
    @endcomponent
@endsection

@section("bodyID", "flowPage")

@section('content')
    <?php $startTime = microtime(true); ?>
    <div class="container">
        <div class="row">
            <section id="courseFlow" class="col-md-8 col-md-offset-2">
                @component("flow.course",["course" => $course, "role" => $role])
                @endcomponent
            </section>
        </div>
    </div>
    <div id="fader">
        <div id="modal"></div>
    </div>
@endsection
<?php $endTime = microtime(true); ?>
<div id="debug">
    <p>Time: {{round($endTime-$startTime,2)}} seconds</p>
    <button onclick="addPopup('test message','error');">Add Popup</button>
</div>
@section("scripts-end")
    @parent
<script>
    @if($role->hasPermissionTo(Permissions::COURSE_EDIT))
        makeCourseContentSortable("#courseContent");
    @endif
    var didAction = handleContentControllers("#courseFlow", ".components", true);

    $().ready(function () {
        $("html,body").animate({
            scrollTop: $(".current").offset().top - (parseInt($("body").css("padding-top"))
                + parseInt($("#courseDetails").css("height")) + 10)
        }, 2000
        );
    });

    $(".dates .datepicker").on("focus", function () {
        $(this).datepicker("show");
    });
</script>
@endsection
