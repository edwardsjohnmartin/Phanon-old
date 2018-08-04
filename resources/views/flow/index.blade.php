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
            @component("flow.course",["course" => $course, "role" => $role, 'eagered' => $eagered])
            @endcomponent
        </section>
    </div>
</div> 
<div id="fader">
    <div id="modal"></div>
</div>
@endsection
<?php
$endTime = microtime(true);
?>
<div id="debug">
    <p>Time: {{round($endTime-$startTime,2)}} seconds</p>
    <a href="{{url("flow/".$course->id."?eager=true")}}">Eager On</a>
    <a href="{{url("flow/".$course->id."?eager=false")}}">Eager Off</a>
    </div>
@section("scripts-end")
    @parent
<script>
    $(".sortableConcept").sortable({ items: ".module", handle: ".dragHandle",placeholder:"module" });
    $(".module").disableSelection();
    var didAction = handleContentControllers("#courseFlow",".components",true);

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
