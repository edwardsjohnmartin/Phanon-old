<?php 
$grade = random_int(50,100);
?>
<li class="project">
    <a href="{{url('/code/project/' . $project->id)}}">
    <div class="completion p{{$grade}}">
            <span>
                {{$grade}}%
            </span>
            <div class="slice">
                <div class="bar"></div>
                <div class="fill"></div>
            </div>
        </div>
    <span class="name">{{$project->name}}</span>
    </a>
</li>
