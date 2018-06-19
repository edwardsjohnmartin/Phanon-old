<?php
// brought in and modified from 3PIO.
namespace App;
use App\Course;
use App\Module;
use App\Concept;
use App\Exercise;
use App\Project;
use App\Lesson;
class Importer{
    /**
     * Summary of get_concepts
     * @param mixed $file_string
     * @return Concept[]
     */
    public static function get_concepts($file_string){
        $concepts = [];
        $conceptList = Importer::getStrings($file_string,"Concept");
        foreach($conceptList as $conceptName => $moduleString){
            $concept = new Concept();
            $concept->name = $conceptName;
            $moduleList = Importer::getStrings($moduleString,"Module","<:::>");
            foreach($moduleList as $moduleName => $lessonString){
                $module = new Module();
                $module->name = $moduleName;
                $lessonAndProjectList = Importer::getStrings($lessonString,"Lesson|Project","<:::>");
                //print_r($lessonList);
                foreach($lessonAndProjectList as $objName => $objContent){
                    $objParts = explode(":",$objName);
                    $objContent = str_replace("<:::>","\n",$objContent);
                    if($objParts[0]==="Lesson"){
                        $lesson = new Lesson();
                        $lesson->name = $objParts[1];
                        $lesson->tempExercises = Importer::get_exercises($objContent);
                        $module->tempComponents[] = $lesson;
                    }else{
                        $project = new Project();
                        $project->name = $objParts[1];
                        //print_r($project);
                        //print_r($objContent);
                        Importer::fill_project($project,$objContent);
                        $module->tempComponents[] = $project;
                    }

                }
                $concept->tempModules[] = $module;
            }
            $concepts[] = $concept;
        }
        return $concepts;
    }

    // public static function get_concepts($file_string){
    //    $concepts = [];
    //    $conceptList = Importer::getStrings($file_string,"Concept");
    //    //echo "<pre>";
    //    //print_r($conceptList);
    //    //echo "</pre>";
    //    foreach($conceptList as $conceptName => $moduleString){
    //        $concept = new Concept();
    //        $concept->name = $conceptName;
    //        $moduleList = Importer::getStrings($moduleString,"Module","<:::>");
    //        //echo "<pre>";
    //        //print_r($moduleList);
    //        //echo "</pre>";
    //        //$modules = [];
    //        foreach($moduleList as $moduleName => $lessonString){
    //            $module = new Module();
    //            $module->name = $moduleName;
    //            //  $modules[] = $module;
    //            $module->tempLessons = Importer::get_lessons(str_replace("<:::>","\n",$lessonString));
    //            $concept->tempModules[] = $module;
    //        }
    //        $concepts[] = $concept;
    //    }
    //    return $concepts;
    //}


    public static function get_lessons_and_projects($lessonString){
        //print_r($file_string);

        //$content_regex = '((?:.|\n)*)';
        $content_regex = '((.|\s)*?)';
        $exercise_regex = '(\bEx\b|\bProject\b):'.$content_regex.
            '<d>'.$content_regex.'<\/d>\s*'. // find description/prompt
            '<s>'.$content_regex.'<\/s>\s*'. // find pre_code
            '((<l>'.$content_regex.'<\/l>)'. // find solution
                    '|(<t>'.$content_regex.'<\/t>))'; // OR find test_code

        $regex_string = '/Lesson: '.$content_regex.'(\s*' . $exercise_regex . ')+/s';

        $lessons = [];
        //pcre.jit = false;
        //ini_set("pcre.jit", 0);
        //ini_set('pcre.jit', false);

        //print_r($exercise_regex);
        if (preg_match_all($regex_string, $lessonString, $matches, PREG_OFFSET_CAPTURE)){
			//if (preg_match_all(static::$regex_string, $file_string, $matches, PREG_OFFSET_CAPTURE))

            //print_r(count($matches[0]));
            for ($i=0; $i < count($matches[0]); $i++){
                $lesson = new Lesson(); 	//current lesson
                $lesson ->name = $matches[1][$i][0];	//name of the current lesson

                preg_match_all('/('.$exercise_regex.')+/s', $matches[0][$i][0], $ex_proj_matches, PREG_OFFSET_CAPTURE);
                //preg_match_all(static::$exercise_regex, $matches[0][$i][0], $ex_proj_matches, PREG_OFFSET_CAPTURE);

                $exercises = [];	//Holds the exercises for the current lesson
                $projects = [];	//Holds the exercises for the current lesson

                for ($j=0; $j < count($ex_proj_matches[0]); $j++){

                    //print_r($ex_proj_matches);

                    //TODO: May need to see if the RegEx can be simplified to have a smaller set
                    $type = $ex_proj_matches[2][$j][0]; // Ex || Project
                    $name = $ex_proj_matches[3][$j][0]; // Name of project; exercises do not have names, but if they did they would be here too.
                    $prompt = $ex_proj_matches[5][$j][0]; // Prompt text (everything in <d></d>)
                    $start_code = $ex_proj_matches[7][$j][0]; // starter code (everything in <s></s>)
                    $solution_code = $ex_proj_matches[11][$j][0]; // solution code (everything in <l></l>)
                    $test_code = $ex_proj_matches[14][$j][0]; // test code (everything in <t></t>)

                    if($type === "Ex"){
                        $exercise = new Exercise();
                        $exercise->prompt = $prompt;
                        $exercise->start_code = $start_code;
                        $exercise->test_code = $test_code;

                        $exercises[] = $exercise;
                    }elseif($type === "Project"){

                        $project = new Project();
                        $project->name = $name;
                        $project->prompt = $prompt;
                        $project->pre_code = ""; // I have not seen pre code yet, but if it exists, it will go here.
                        $project->start_code = $start_code;
                        $project->solution = $solution_code;

                        $projects[] = $project;

                    }else{
                        // should not hit this.
                    }
                }

                $lesson->tempExercises = $exercises;
                $lesson->tempProjects = $projects;
                //$l_attributes = array('name' => $lesson_name, 'exercises' => $exercises);
                //$lesson->set_properties($l_attributes);

                $lessons[] = $lesson;
            }
        }else{
            print_r($lessonString);
            $err = preg_last_error();
            if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('Backtrack limit was exhausted!');
            } else if ($err == PREG_NO_ERROR) {
                print_r('PREG_NO_ERROR');
            } else if ($err == PREG_INTERNAL_ERROR) {
                print_r('PREG_INTERNAL_ERROR');
            } else if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('PREG_BACKTRACK_LIMIT_ERROR');
            } else if ($err == PREG_RECURSION_LIMIT_ERROR) {
                print_r('PREG_RECURSION_LIMIT_ERROR');
            } else if ($err == PREG_BAD_UTF8_ERROR) {
                print_r('PREG_BAD_UTF8_ERROR');
            } else if ($err == PREG_BAD_UTF8_OFFSET_ERROR) {
                print_r('PREG_BAD_UTF8_OFFSET_ERROR');
            } else if ($err == PREG_JIT_STACKLIMIT_ERROR) {
                print_r('PREG_JIT_STACKLIMIT_ERROR');
            }
            print_r('Failed to parse');
        }
        return $lessons;
    }


    /**
     * Summary of get_lessons
     * @param mixed $lessonString
     */
    public static function get_lessons($lessonString){
        //print_r($file_string);

        //$content_regex = '((?:.|\n)*)';
        $content_regex = '(.*?)';
        $exercise_regex = 'Ex:\s*<d>\s*'.$content_regex.'<\/d>\s*<s>\s*'.$content_regex.'<\/s>\s*<t>\s*'.$content_regex.'<\/t>';
        //$exercise_regex = 'Ex:\s*<desc>\s*'.$content_regex.'<\/desc>\s*<starter>\s*'.$content_regex.'<\/starter>\s*<test>\s*'.$content_regex.'<\/test>';
        $regex_string = '/Lesson: ([^\n]+)(\s*' . $exercise_regex . ')+/s';

        $lessons = [];
        //pcre.jit = false;
        //ini_set("pcre.jit", 0);
        //ini_set('pcre.jit', false);

        //print_r($exercise_regex);
        if (preg_match_all($regex_string, $lessonString, $matches, PREG_OFFSET_CAPTURE)){
			//if (preg_match_all(static::$regex_string, $file_string, $matches, PREG_OFFSET_CAPTURE))

            //print_r(count($matches[0]));
            for ($i=0; $i < count($matches[0]); $i++){
                $lesson = new Lesson(); 	//current lesson
                $lesson ->name = $matches[1][$i][0];	//name of the current lesson

                preg_match_all('/('.$exercise_regex.')+/s', $matches[0][$i][0], $exercise_matches, PREG_OFFSET_CAPTURE);
                //preg_match_all(static::$exercise_regex, $matches[0][$i][0], $exercise_matches, PREG_OFFSET_CAPTURE);

                $exercises = [];	//Holds the exercises for the current lesson

                for ($j=0; $j < count($exercise_matches[0]); $j++){
                    $exercise = new Exercise();

                    $exercise->prompt = $exercise_matches[2][$j][0];
                    $exercise->start_code = $exercise_matches[3][$j][0];
                    $exercise->test_code = $exercise_matches[4][$j][0];

                    $exercises[] = $exercise;
                }

                $lesson->tempExercises = $exercises;
                //$l_attributes = array('name' => $lesson_name, 'exercises' => $exercises);
                //$lesson->set_properties($l_attributes);

                $lessons[] = $lesson;
            }
        }else{
            print_r($lessonString);
            $err = preg_last_error();
            if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('Backtrack limit was exhausted!');
            } else if ($err == PREG_NO_ERROR) {
                print_r('PREG_NO_ERROR');
            } else if ($err == PREG_INTERNAL_ERROR) {
                print_r('PREG_INTERNAL_ERROR');
            } else if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('PREG_BACKTRACK_LIMIT_ERROR');
            } else if ($err == PREG_RECURSION_LIMIT_ERROR) {
                print_r('PREG_RECURSION_LIMIT_ERROR');
            } else if ($err == PREG_BAD_UTF8_ERROR) {
                print_r('PREG_BAD_UTF8_ERROR');
            } else if ($err == PREG_BAD_UTF8_OFFSET_ERROR) {
                print_r('PREG_BAD_UTF8_OFFSET_ERROR');
            } else if ($err == PREG_JIT_STACKLIMIT_ERROR) {
                print_r('PREG_JIT_STACKLIMIT_ERROR');
            }
            print_r('Failed to parse');
        }
        return $lessons;
    }
    /**
     * Summary of get_exercises
     * @param mixed $lessonString
     * @return Exercise[]
     */
    public static function get_exercises($exerciseString){
        //print_r($file_string);

        //$content_regex = '((?:.|\n)*)';
        $content_regex = '(.*?)';
        $exercise_regex = 'Ex:\s*<d>\s*'.$content_regex.'<\/d>\s*<s>\s*'.$content_regex.'<\/s>\s*<t>\s*'.$content_regex.'<\/t>';

        $exercises = [];//Holds the exercises for the current lesson

        if (preg_match_all('/('.$exercise_regex.')+/s', $exerciseString, $exercise_matches, PREG_OFFSET_CAPTURE)){

            for ($j=0; $j < count($exercise_matches[0]); $j++){
                $exercise = new Exercise();

                $exercise->prompt = $exercise_matches[2][$j][0];
                $exercise->start_code = $exercise_matches[3][$j][0];
                $exercise->test_code = $exercise_matches[4][$j][0];

                $exercises[] = $exercise;
            }
        }else{
            print_r($exerciseString);
            $err = preg_last_error();
            if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('Backtrack limit was exhausted!');
            } else if ($err == PREG_NO_ERROR) {
                print_r('PREG_NO_ERROR');
            } else if ($err == PREG_INTERNAL_ERROR) {
                print_r('PREG_INTERNAL_ERROR');
            } else if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('PREG_BACKTRACK_LIMIT_ERROR');
            } else if ($err == PREG_RECURSION_LIMIT_ERROR) {
                print_r('PREG_RECURSION_LIMIT_ERROR');
            } else if ($err == PREG_BAD_UTF8_ERROR) {
                print_r('PREG_BAD_UTF8_ERROR');
            } else if ($err == PREG_BAD_UTF8_OFFSET_ERROR) {
                print_r('PREG_BAD_UTF8_OFFSET_ERROR');
            } else if ($err == PREG_JIT_STACKLIMIT_ERROR) {
                print_r('PREG_JIT_STACKLIMIT_ERROR');
            }
            print_r('Failed to parse');
        }
        return $exercises;
    }

    public static function fill_project($project, $projectString){

        $content_regex = '((.|\s)*?)';
        $project_regex = '<d>'.$content_regex.'<\/d>\s*'. // find description/prompt
            '<s>'.$content_regex.'<\/s>\s*'. // find pre_code
            '<l>'.$content_regex.'<\/l>'; // find solution

        if (preg_match_all('/(\s*' . $project_regex . ')+/s', $projectString, $matches, PREG_OFFSET_CAPTURE)){
                    //print_r($matches);
                    //TODO: May need to see if the RegEx can be simplified to have a smaller set
                    $prompt = $matches[2][0][0]; // Prompt text (everything in <d></d>)
                    $start_code = $matches[4][0][0]; // starter code (everything in <s></s>)
                    $solution_code = $matches[6][0][0]; // solution code (everything in <l></l>)

                    $project->prompt = $prompt;
                    $project->pre_code = ""; // I have not seen pre code yet, but if it exists, it will go here.
                    $project->start_code = $start_code;
                    $project->solution = $solution_code;

        }else{
            print_r($projectString);
            $err = preg_last_error();
            if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('Backtrack limit was exhausted!');
            } else if ($err == PREG_NO_ERROR) {
                print_r('PREG_NO_ERROR');
            } else if ($err == PREG_INTERNAL_ERROR) {
                print_r('PREG_INTERNAL_ERROR');
            } else if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
                print_r('PREG_BACKTRACK_LIMIT_ERROR');
            } else if ($err == PREG_RECURSION_LIMIT_ERROR) {
                print_r('PREG_RECURSION_LIMIT_ERROR');
            } else if ($err == PREG_BAD_UTF8_ERROR) {
                print_r('PREG_BAD_UTF8_ERROR');
            } else if ($err == PREG_BAD_UTF8_OFFSET_ERROR) {
                print_r('PREG_BAD_UTF8_OFFSET_ERROR');
            } else if ($err == PREG_JIT_STACKLIMIT_ERROR) {
                print_r('PREG_JIT_STACKLIMIT_ERROR');
            }
            print_r('Failed to parse');
        }
    }

    //public static function get_exams($file_string){
    //    $content_regex = '(.*?)';
    //    $question_regex = 'Q:\s*<w>\s*'.$content_regex.'<\/w>\s*<d>\s*'.$content_regex.'<\/d>\s*<s>\s*'.$content_regex.'<\/s>\s*<t>\s*'.$content_regex.'<\/t>';
    //    $regex_string = '/Exam: ([^\n]+)(\s*' . $question_regex . ')+/s';
    //    $exams = [];
    //    if (preg_match_all($regex_string, $file_string, $matches, PREG_OFFSET_CAPTURE)){
    //        for ($i=0; $i < count($matches[0]); $i++){
    //            $exam_name = $matches[1][$i][0];	//name of the current exam
    //            $exam = new Exam(); 	//current exam
    //            preg_match_all('/('.$question_regex.')+/s', $matches[0][$i][0], $question_matches, PREG_OFFSET_CAPTURE);
    //            $questions = [];	//Holds the questions for the current exam
    //            for ($j=0; $j < count($question_matches[0]); $j++){
    //                $question = new Question();
    //                $weight = $question_matches[2][$j][0];
    //                $instructions = $question_matches[3][$j][0];
    //                $start_code = $question_matches[4][$j][0];
    //                $test_code = $question_matches[5][$j][0];
    //                //For now, I'm just assuming that the language is Python, which is why 'language' is always 1.
    //                $q_attributes = array('weight' => $weight, 'instructions' => $instructions, 'start_code' => $start_code, 'test_code' => $test_code);
    //                $question->set_properties($q_attributes);
    //                $questions[] = $question;
    //            }
    //            $x_attributes = array('name' => $exam_name, 'questions' => $questions, 'instructions' => 'Default instructions');
    //            $exam->set_properties($x_attributes);
    //            $exams[] = $exam;
    //        }
    //    }else{
    //        print_r($file_string);
    //        $err = preg_last_error();
    //        if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
    //            print_r('Backtrack limit was exhausted!');
    //        } else if ($err == PREG_NO_ERROR) {
    //            print_r('PREG_NO_ERROR');
    //        } else if ($err == PREG_INTERNAL_ERROR) {
    //            print_r('PREG_INTERNAL_ERROR');
    //        } else if ($err == PREG_BACKTRACK_LIMIT_ERROR) {
    //            print_r('PREG_BACKTRACK_LIMIT_ERROR');
    //        } else if ($err == PREG_RECURSION_LIMIT_ERROR) {
    //            print_r('PREG_RECURSION_LIMIT_ERROR');
    //        } else if ($err == PREG_BAD_UTF8_ERROR) {
    //            print_r('PREG_BAD_UTF8_ERROR');
    //        } else if ($err == PREG_BAD_UTF8_OFFSET_ERROR) {
    //            print_r('PREG_BAD_UTF8_OFFSET_ERROR');
    //        } else if ($err == PREG_JIT_STACKLIMIT_ERROR) {
    //            print_r('PREG_JIT_STACKLIMIT_ERROR');
    //        }
    //        print_r('Failed to parse');
    //    }
    //    return $exams;
    //}
    /**
     * Summary of getStrings
     * @param mixed $str
     * @param mixed $identifier
     * @param mixed $delimeter what lines are separated by. default (new line)
     * @return string[]
     */
    static function getStrings($str,$identifier,$delimeter = "\n"){
        $strings =[];
        $lines = explode($delimeter,str_replace("\r","",$str));
        $string = "";
        $name = "";
        $identifiers = explode("|",$identifier);

        foreach($lines as $line){
            $stop = strpos($line,":");
            $isMatch = false;
            foreach($identifiers as $idName){
                if(substr($line,0,$stop) === $idName){
                    $isMatch = true;
                }
            }
            if($isMatch){
                // new object
                if($string !== "") {
                    $strings[$name] = $string;
                    $string = "";
                }
                if(count($identifiers) > 1){
                    $name = trim($line);
                }else{
                    $name = trim(substr($line,$stop + 1));
                }
            }else{
                $trimmed = trim($line);
                if ($trimmed !== "") $string .= $line."<:::>";
            }

        }
        // make sure last string is added
        if($string !== "") $strings[$name] = $string;

        return $strings;
    }

}
?>
