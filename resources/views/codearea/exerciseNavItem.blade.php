<li id="exercise_{{$exercise->id}}" data-item-count="{{$exercise_count}}" class="exercise mini {{$class}}"
    @if($role->hasPermissionTo(Permissions::EXERCISE_EDIT)) tooltip="{{$exercise->prompt}}" @endif>
    @if($is_active) 
        <a title="Open this exercise {{$exercise_count}} in the editor."
           href="{{url('code/exercise/' . $exercise->id)}}">
            {{$exercise_count}}
        </a>
            @if($role->hasPermissionTo(Permissions::EXERCISE_EDIT))
        <span class="nodeDetails">{{$exercise->id}}</span>
        @endif>
    @endif
</li>