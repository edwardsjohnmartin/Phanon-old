<?php
$exerciseCounter = 0;
?>
<ol id="exerciseList">
    @foreach($lessons as $lesson)
        @foreach($lesson->exercises() as $exercise)
            <?php $exerciseCounter++ ?>
            <li class="exercise mini" data-lesson-id="{{$lesson->id}}">
                {{$exerciseCounter}}
                <span class="lessonCode">{{$lesson->id}}</span>
            </li>
        @endforeach
    @endforeach
</ol>