<?php
$exerciseCounter = 0;
?>
<ol id="exerciseList">
    @foreach($exercises as $exercise)
    <?php $exerciseCounter++ ?>
    <li class="exercise mini">{{$exerciseCounter++}}</li>
    @endforeach
</ol>