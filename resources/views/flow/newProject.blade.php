<li class="project">
    <a href="{{url('/code/project/' . $project->id)}}">
        <div class="completion p50">
                <span>50%</span>
                <div class="slice">
                    <div class="bar"></div>
                    <div class="fill"></div>
                </div>
            </div>
        <span class="name">{{$project->name}}</span>
    </a>
</li>
