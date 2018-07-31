<div class="hidden">
    <p id="projectId">{{$project->id}}</p>
</div>

<div id="ideProjectName">
    <h1 id="projectName" class="editable">{{$project->name}}</h1>
</div>

@component('codearea.prompt', [
    'prompt' => $project->prompt,
    'show_survey' => true,
    'team' => $team,
    'projectSurveyResponse' => $projectSurveyResponse,
    'item_type' => $item_type
])
@endcomponent

<div id="ideProjectDates" class="hidden">
    <label for="open_date">Open Date</label>
    <input id="projectOpenDate" type="datetime-local" value="{{$project->getOpenDate('Y-m-d\TH:i')}}"/>

    <label for="close_date">Close Date</label>
    <input id="projectCloseDate" type="datetime-local" value="{{$project->getCloseDate('Y-m-d\TH:i')}}"/>
</div>

<div id="ideTeamsSetting" class="hidden">
    <label for="teams_enabled">Teams Enabled</label>
    <input id="projectTeamsSetting" type="checkbox" value="{{$project->teamsEnabled()}}" @if($project->teamsEnabled()) checked @endif/>
</div>

@component('codearea.precode', ['pre_code' => $project->pre_code])
@endcomponent
