@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/actions')
    @endcomponent

    @component("scriptbundles/percentages")
    @endcomponent

    @component("scriptbundles/course-flow")
    @endcomponent
@endsection

@section('content')

<div class="container">
    <div class="row">
        <section id="courseFlow" class="col-md-8 col-md-offset-2">
            @component("flow.course",["course" => $course, "role" => $role])
            @endcomponent
        </section>
    </div>

    <div class="row create-button-div hidden">
        <button class="center-block" onclick="createConcept({{$course->id}}, '{{url('/ajax/conceptcreate')}}')">Create New Concept</button>
    </div>
</div>
@endsection

@section("scripts-end")
@parent
<script>
    $(".sortableConcept").sortable({ items: ".module" });
    $(".module").disableSelection();
    var didAction = handleContentControllers("#courseFlow",".components",true);

    $().ready(function () {
        $("html,body").animate({
            scrollTop: $(".current").offset().top - (parseInt($("body").css("padding-top"))
                + parseInt($("#courseDetails").css("height")) + 10)
        }, 2000
        );
    });
</script>
@endsection
