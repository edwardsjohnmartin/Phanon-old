<li class="lesson">
    <a href="{{url('/code/'.$lesson->module_id.'/' . $lesson->id)}}">
        <span>Lesson {{$lesson->name}}</span>
        <span class="itemCount">{{count($lesson->exercises())}}</span>
    </a>
    
</li>