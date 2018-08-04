<?php
//use App\Project;
//$project = new Project();
//print_r($project);
$now = Carbon\Carbon::now();
//$now = date_add($now,date_interval_create_from_date_string("7 days"));// add a week for testing.
$css_class = "none";
$status_text = "nothing";
$status_open_tense = "s"; // s for still available. ed for done.
$status_close_tense = "es"; // es for still available. ed for done.
if($project->open_date > $now){
    $css_class = "pending";
    $status_text = "Not Open";
}elseif($project->open_date < $now && $project->close_date > $now){
    $css_class = "open";
    $status_text = "Open";
    $status_open_tense = "ed";
}elseif($project->close_date < $now){
    $css_class = "closed";
    $status_text = "Closed";
    $status_open_tense = "ed";
    $status_close_tense = "ed";
}else{ // in progress
    $css_class = "inProgress";
    $status_text = "In Progress";
    $status_open_tense = "ed";
}
?>
<li id="project_{{$project->id}}" class="project {{$css_class}}">
    @if($role->hasPermissionTo(Permissions::PROJECT_EDIT))
    <div class="actions">
        <button class="edit" data-item-type="project" data-item-id="{{$project->id}}"
             >Edit</button>
        @if($project->teams_enabled)
            <button class="teams" onclick="displayTeamsList({{$project->id}})"
                    tooltip="Show teams for this project">Show Teams</button>
        @endif
        </div>
    @endif
    <a href="{{url('/code/project/' . $project->id)}}">
        <div class="projectStatus">
            <span>{{$status_text}}</span>
        </div>
        <span class="name">{{$project->name}}</span>
        <span class="teams{{$project->teams_enabled ? " enabled": " disabled"}}"
              >{{$project->teams_enabled ? "Enabled": "Disabled"}}</span>
        <dl class="dates">
            <dt>Open{{$status_open_tense}}</dt>
            <dd>{{$project->getOpenDate(config("app.dateformat_short"))}}</dd>
            <dt>Clos{{$status_close_tense}}</dt>
            <dd>{{$project->getCloseDate(config("app.dateformat_short"))}}</dd>
        </dl>
    </a>
</li>
