<?php
$startdate = $module->open_date;
$now = date(config("app.dateformat"));
$isPast = $startdate < $now;
?>
<article class="module{{$isPast  ? ' expired' : '' }}">
    <h1>
        {{-- #todo: need to fix this to acutally use the correct exercise --}}
        <a href="{{ $isPast 
            ? url('/code/review/'.$module->id.'/'.$module->id)
            : url('/code/'.$module->id.'/'.$module->id)}}">
        {{$module->name}}</a>
    </h1>
    <aside class="actions">
        <a class="edit" href="{{url('/modules/' . $module->id . '/edit')}}">Edit</a>
        <a class="copy" href="{{url('/modules/' . $module->id . '/copy')}}">copy</a>
        <a class="delete" href="{{url('/modules/' . $module->id . '/destroy')}}">Delete</a>
    </aside>
    <div class="dates">
        <!--TODO: these dates should come preformatted-->
        <!--Not sure why we are parsing them then reformatting them again.-->
        <span class="start">{{date_format($module->OpenDate(),config("app.dateformat_short"))}}</span>
    </div>
    <ul class="lessons">
        @foreach($module->lessons() as $less)
            @component('flow.lesson',['lesson' => $less])
            @endcomponent
        @endforeach
    </ul>
    <ul class="projects">
        @foreach($module->projects() as $proj)
            @component('flow.project',['project' => $proj])
            @endcomponent
        @endforeach
    </ul>

</article>
