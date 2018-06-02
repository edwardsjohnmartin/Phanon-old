<?php
$startdate = DateTime::createFromFormat('Y-m-d G:i:s', $module->open_date);
$now = DateTime::createFromFormat('Y-m-d G:i:s',date('Y-m-d G:i:s'));
$isPast = $startdate < $now;
?>
<article class="module{{$isPast  ? ' expired' : '' }}">
    <h1>{{$module->name}}</h1>
    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="copy" href="{{url('/modules/' . $module->id . '/copy')}}">copy</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>
    <div class="dates">
        <!--TODO: these dates should come preformatted-->
        <!--Not sure why we are parsing them then reformatting them again.-->
        <span class="start">{{date_format($startdate, 'm/d/Y')}}</span>
    </div>
    <ul class="lessons">
        @foreach($module->lessons() as $less)
            @component('lessons.flow',['lesson' => $less])
            @endcomponent
        @endforeach
    </ul>
    <ul class="projects">
        @foreach($module->projects() as $proj)
            @component('projects.flow',['project' => $proj])
            @endcomponent
        @endforeach
    </ul>
    <div class="details">
        <span class="author">Author: {{$module->user->name}}</span>
        <span class="added">Created On: {{$module->created_at}}</span>
        <span class="modified">Last Updated At: {{$module->updated_at}}</span>
    </div>
</article>
