@php
    // make sure show_survey is set
    if(!isset($show_survey)) $show_survey = false;
    if(!isset($team)) $team = null;

    if(isset($projectSurveyResponse)){
        $blockCodeWindow = false;
        $diffuculty_rating = $projectSurveyResponse->difficulty_rating;
        $enjoyment_rating = $projectSurveyResponse->enjoyment_rating;
    } else {
        $projectSurveyResponse = false;
        $blockCodeWindow = true;
        $diffuculty_rating = -1;
        $enjoyment_rating = -1;
    }
@endphp

@section("scripts")
    @parent
    @component('scriptbundles/actions')
    @endcomponent

<script>
    function selectRating(identifier, selected, max) {
        for (var i = 0; i <= max; i++) {
            var rating = $("#" + identifier + "_" + i);
            rating.removeClass("selected");
            if (i <= selected) rating.addClass("selected");
        }
    }
</script>
@endsection

<div id="idePrompt">
    <div id="promptContainer" class="editable">
        <h3>Instructions</h3>
        <section id="promptInstructions" data-raw-prompt="{{$prompt}}">{!!$prompt !!}</section>
    </div>
    {{-- needs to start open on the first approach  --}}
    <button class="contentControl collapser" {{$show_survey?'disabled="disabled"':''}}>Show/Hide Contents</button>

    @if($item_type == 'project' and $show_survey)
    <div id="projectRatings" data-survey-response-create-url="{{url('/ajax/projectsurveycreate')}}">
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

    @if($item_type == 'project' and $team != null)
        @component('codearea.team', ['team'=>$team])
        @endcomponent
    @endif
</div>
    @if($item_type == 'project' and $blockCodeWindow)
        <div id="fader">
            <div class="message">
                <h1>Please rate your first impression of this project.</h1>
                <p>Don't worry, you will be still be able modify your ratings as you work on the project.</p>
            </div>
        </div>
    @endif

@section("scripts-end")
    @parent
    @if($show_survey)
<script>
   handleContentControllers("#idePrompt", "#promptInstructions");

    // These are now set using PHP variables
    var difficultRating = '{{$diffuculty_rating}}';
    var enjoymentRating = '{{$enjoyment_rating}}';

    // set visibility of controls for doing project as needed.
    if (difficultRating >= 0) selectRating("difficulty", difficultRating, 9);
    if (enjoymentRating >= 0) selectRating("enjoyment", enjoymentRating, 9);

    setInstructionsPaneControls();

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
            setInstructionsPaneControls();
            // Call function that makes AJAX call to save survey results to the database. It is located in codeeditor.js
            createProjectSurveyResponse(difficultRating, enjoymentRating);
        }
    });

    function setInstructionsPaneControls() {
        $("#fader").animate({ height: 0 }, 400, function () {
            $(this).hide();
        });
        // for debugging you can uncomment the next line.
        //$("#output").text("difficulty: " + difficultRating + " enjoyment: " + enjoymentRating);
        $(".contentControl").attr("disabled", false);
    }


            //// Fake click event on the ratings of the users last response
            //var difficulty_rating = '{{$projectSurveyResponse->difficulty_rating}}';
            //var enjoyment_rating = '{{$projectSurveyResponse->enjoyment_rating}}';

            // moved this up top to reuse existing functions
</script>
@endif
    @endsection
