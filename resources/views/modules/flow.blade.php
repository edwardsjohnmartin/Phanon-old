<article class="module">
    <h1>{{$module->name}}</h1>
    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="clone" href="{{url('/modules/' . $module->id . '/clone')}}">Clone</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>
    <div class="dates">
        <!--TODO: these dates should come preformatted-->
        <!--Not sure why we are parsing them then reformatting them again.-->
        <span class="start">{{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $module->open_date), 'm/d/Y')}}</span>
    </div>
    <ul class="lessons">
        @foreach($module->lessons() as $less)
        <li class="lesson">
            <a href="{{url('/lessons/' . $less->id)}}">Lesson {{$less->name}}</a>
        </li>
        @endforeach
    </ul>
    <ul class="projects">
        @foreach($module->projects() as $proj)
        <li class="project">
            <a href="{{url('/projects/' . $proj->id)}}">{{$proj->name}}</a>
        </li>
        @endforeach
    </ul>
    <div class="details">
        <span class="author">Author: {{$module->user->name}}</span>
        <span class="added">Created On: {{$module->created_at}}</span>
        <span class="modified">Last Updated At: {{$module->updated_at}}</span>
    </div>
</article>
