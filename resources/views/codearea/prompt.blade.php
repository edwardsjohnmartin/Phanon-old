@php
if(!isset($show_survey)) $show_survey = false;
@endphp
@section("scripts")
    @parent
    @component('scriptbundles/actions')
    @endcomponent
@endsection

<div id="idePrompt">
    <div id="promptContainer">
        <h3>Instructions</h3>
        <section id="promptInstructions">{!!$prompt !!}</section>
    </div>
    {{-- needs to start open on the first approach  --}}
    <button class="contentControl collapser" {{$show_survey?'disabled="disabled"':''}}>Show/Hide Contents</button>
    @if($show_survey)
    <div id="projectRatings">
        <h3>Ratings</h3>
        {{-- Projects have survey buttons.  --}}
        <h4>Difficulty</h4>
        <ol id="projectDifficulty">
            <li id="difficulty_9">9</li>
            <li id="difficulty_8">8</li>
            <li id="difficulty_7">7</li>
            <li id="difficulty_6">6</li>
            <li id="difficulty_5">5</li>
            <li id="difficulty_4">4</li>
            <li id="difficulty_3">3</li>
            <li id="difficulty_2">2</li>
            <li id="difficulty_1">1</li>
            <li id="difficulty_0">0</li>
        </ol>
        <h4>Enjoyment</h4>
        <ol id="projectEnjoyment">
            <li id="enjoyment_9">9</li>
            <li id="enjoyment_8">8</li>
            <li id="enjoyment_7">7</li>
            <li id="enjoyment_6">6</li>
            <li id="enjoyment_5">5</li>
            <li id="enjoyment_4">4</li>
            <li id="enjoyment_3">3</li>
            <li id="enjoyment_2">2</li>
            <li id="enjoyment_1">1</li>
            <li id="enjoyment_0">0</li>
        </ol>
    </div>
    @endif
</div>

@section("scripts-end")
@parent
<script>
    handleContentControllers("#idePrompt", "#promptInstructions");
    var difficultRating = -1, enjoymentRating = -1;
    $("#projectRatings").click(function (evt) {
        evt = evt || window.event;
        var target = evt.target || evt.srcElement;
        if (target.tagName == "LI") {
            // only handle scale clicks
            var tarId = target.id;
            var idParts = tarId.split("_");
            var selType = idParts[0];
            var selIndex = parseInt(idParts[1]);
            if (selType == "difficulty") {
                // difficulty rating
                difficultRating = selIndex;
            } else if (selType == "enjoyment") {
                // enjoyment rating
                enjoymentRating = selIndex;
            } else {
                // should not hit this
            }
            selectRating(selType, selIndex, 9); // baking 9 for now.
        }
        if (difficultRating >= 0 && enjoymentRating >= 0) {
            $("#output").text("difficulty: " + difficultRating + " enjoyment: " + enjoymentRating);
            $(".contentControl").attr("disabled", false);
        }
    });
    function selectRating(identifier, selected, max) {
        for (var i = 0; i <= max; i++) {
            var rating = $("#" + identifier + "_" + i);
            rating.removeClass("selected");
            if (i <= selected) rating.addClass("selected");
        }
    }
</script>
@endsection
