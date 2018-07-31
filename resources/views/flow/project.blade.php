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
<li class="project {{$css_class}}">
    <a href="{{url('/code/project/' . $project->id)}}">
        <div class="projectStatus">
            <span>{{$status_text}}</span>
        </div>
        <span class="name">{{$project->name}}</span>
        <dl class="dates">
            <dt>Open{{$status_open_tense}}</dt>
            <dd>{{$project->getOpenDate(config("app.dateformat_short"))}}</dd>
            <dt>Clos{{$status_close_tense}}</dt>
            <dd>{{$project->getCloseDate(config("app.dateformat_short"))}}</dd>

        </dl>
    </a>
</li>
