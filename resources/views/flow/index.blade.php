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
            }
        }
    });
    $().ready(function () {
        $("html,body").animate({
            scrollTop: $(".current").offset().top
        }, 2000
        );
    });
</script>
@endsection