@extends("layouts.app")

@section("content")
{{--
<dl>
    @foreach($lessons as $lesson)
    <dt>
        {{$lesson->name
}}
    </dt>
    @foreach($lesson->tempExercises as $exercise)
    <dd>
        <dl>
            <dt>name</dt>
            <dd>{{$exercise->name}}</dd>
            <dt>prompt</dt>
            <dd>{{$exercise->prompt}}</dd>
            <dt>Pre Code</dt>
            <dd>
                <textarea>{{$exercise->pre_code}}</textarea>
            </dd>
            <dt>Starter Code</dt>
            <dd>
                <textarea>{{$exercise->starter_code}}</textarea>
            </dd>
            <dt>Test Code</dt>
            <dd>
                <textarea>{{$exercise->test_code}}</textarea>
            </dd>
        </dl>

    </dd>
    @endforeach
@endforeach
</dl>
--}}
<?php
// create Seed from Uploaded files.
$moduleID = 1;
$conceptID = 1;
$lessonID = 1;
$exerciseID = 1;
$outputText = "";
$eOL = "";
$startDate = new DateTime("2018-05-14");

foreach($concepts as $concept){
    $outputText .='$concept'.$conceptID.'= Concept::create(['.
        "'name' => '".trim($concept->name)."',".$eOL.
        "'course_id' => ".'$course->id'.",".$eOL;
    if ($conceptID > 1)
        $outputText .= "'previous_concept_id' => ".'$concept'.($conceptID-1).'->id,'.$eOL;
    $outputText .= "'user_id' => ".'$user->id'."]);$eOL$eOL"."\r\n";
    
    $moduleCount = 0;
    foreach($concept->tempModules as $module){
        $moduleCount++;
        $modStartDate = new DateTime(date_format($startDate,"Y-m-d"));
        $modStartDate->add(new DateInterval("P".$moduleCount."D"));
        $outputText .='$module'.$moduleID.'= Module::create(['.
            "'name' => '".trim($module->name)."',$eOL".
            "'concept_id' => ".'$concept'.$conceptID."->id,$eOL".
            "'open_date' => '".$modStartDate->format(config("app.dateformat"))."',$eOL";
        if ($moduleCount > 1)
            $outputText .= "'previous_module_id' => ".'$module'.($moduleID-1).'->id,'.$eOL;
        $outputText .= "'user_id' => ".'$user->id'."]);$eOL$eOL"."\r\n";
    
        $lessonCount = 0;
        foreach($module->tempLessons as $lesson){
            $lessonCount++;
            $outputText .='$lesson'.$lessonID.'= Lesson::create(['.
                "'name' => '".trim($lesson->name)."',".$eOL.
                "'module_id' => ".'$module'.$moduleID."->id,$eOL";
            if ($lessonCount > 1)
                $outputText .= "'previous_lesson_id' => ".'$lesson'.($lessonID-1).'->id,'.$eOL;
            $outputText .= "'user_id' => ".'$user->id'."]);$eOL$eOL"."\r\n";

     
            $exerciseCount = 0;
            foreach($lesson->tempExercises as $exercise){
                $exerciseCount++;
                $prompt = str_replace("\r",'\r',str_replace("\n",'\n',$exercise->prompt));
                $pre_code = str_replace("\r",'\r',str_replace("\n",'\n',$exercise->pre_code));
                $start_code = str_replace("\r",'\r',str_replace("\n",'\n',$exercise->start_code));
                $test_code = str_replace("\r",'\r',str_replace("\n",'\n',$exercise->test_code));

                $outputText .='$exercise'.$exerciseID.' = Exercise::create(['.
                "'prompt' => '$prompt',$eOL".
                "'pre_code' => '$pre_code',$eOL".
                "'start_code' => '$start_code',$eOL".
                "'test_code' => '$test_code',$eOL".
                "'lesson_id' => ".'$lesson'.$lessonID."->id,$eOL";
                if ($exerciseCount > 1)
                    $outputText .= "'previous_exercise_id' => ".'$exercise'.($exerciseID-1).'->id,'.$eOL;
                $outputText .= "'user_id' => ".'$user->id'."]);$eOL$eOL"."\r\n";
                $exerciseID++;
            }
            $lessonID++;
        }
        $moduleID++;

    }
    $startDate->add(new DateInterval("P1W"));
    $conceptID++;
}
?>
<pre>{{$outputText}}</pre>
@endsection