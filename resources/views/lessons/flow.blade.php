<li class="lesson">
    <a href="{{url('/lessons/' . $lesson->id)}}">
        <span>Lesson {{$lesson->name}}</span>
        <span class="itemCount">{{count($lesson->exercises())}}</span>
    </a>
    
</li>