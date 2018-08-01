<li class="exercise mini {{$class}}">
    @if($is_active) 
        <a href="{{url('code/exercise/' . $exercise->id)}}">{{$exercise_count}}</a>
    @endif
</li>