<?php
$exerciseCounter = 0;
?>
<ol id="exerciseList">
    @foreach($lessons as $lesson)
        @foreach($lesson->exercises() as $exercise)
            <?php $exerciseCounter++ ?>
            <li class="exercise mini">{{$exerciseCounter}}</li>
        @endforeach
    @endforeach
</ol>