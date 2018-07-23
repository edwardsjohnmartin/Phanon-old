@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent

    @component("scriptbundles/percentages")
    @endcomponent
@endsection

@section('content')

<div class="container">
    <div class="row">
        <section id="courseFlow" class="col-md-8 col-md-offset-2">
            @component("flow.course",["course" => $course])
            @endcomponent
        </section>
    </div>

    <div class="row edit-button-div" style="visibility: hidden; display: none">
        <button class="center-block" onclick="createConcept({{$course->id}})">Create New Concept</button>
    </div>
</div>
@endsection

@section("scripts-end")
@parent
<script>
    $(".sortableConcept").sortable({ items: ".module" });
    $(".module").disableSelection();
    // contentControl event logic
    $("#courseFlow").click(function (e) {
        e = e || window.event;
        var t = e.target || e.srcElement;
        if (t.tagName === "BUTTON") {
            // had to name these expander and collapser because of BootStrap
            var wasAction = true;
            if (t.classList.contains("expander")) {
                // only handle content Controls
                $(t).removeClass("expander").addClass("collapser");
            } else if (t.classList.contains("collapser")) {
                $(t).removeClass("collapser").addClass("expander");
            } else {
                // other buttons we are not handling here.
                wasAction = false;
            }
            if (wasAction) {
                $(t).parent().find(".components").animate({ height: "toggle" });
                $("html,body").animate({
                    scrollTop: $(t).parent().offset().top - (parseInt($("body").css("padding-top"))
                        + parseInt($("#courseDetails").css("height")))
                }, 2000
                );
            }
        }
    });
    $().ready(function () {
        $("html,body").animate({
            scrollTop: $(".current").offset().top - (parseInt($("body").css("padding-top"))
                + parseInt($("#courseDetails").css("height")) + 10)
        }, 2000
        );
    });
</script>
@endsection