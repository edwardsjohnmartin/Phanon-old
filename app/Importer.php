<?php
// brought in and modified from 3PIO.
namespace App;
use App\Course;
use App\Module;
use App\Concept;
use App\Exercise;
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
        //echo "<pre>";
        //print_r($conceptList);
        //echo "</pre>";
        foreach($conceptList as $conceptName => $moduleString){
            $concept = new Concept();
            $concept->name = $conceptName;
            $moduleList = Importer::getStrings($moduleString,"Module","<:::>");
            //echo "<pre>";
            //print_r($moduleList);
            //echo "</pre>";
            //$modules = [];
            foreach($moduleList as $moduleName => $lessonString){
                $module = new Module();
                $module->name = $moduleName;
                //  $modules[] = $module;
                $module->tempLessons = Importer::get_lessons(str_replace("<:::>","\n",$lessonString));
                $concept->tempModules[] = $module;
            }
            $concepts[] = $concept;
        }
        return $concepts;
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
        $lines = explode($delimeter,$str);
        $string = "";
        $name = "";
        foreach($lines as $line){
            $stop = strpos($line,":");
            if(substr($line,0,$stop) === $identifier){
                // new module
                if($string !== "") {
                    $strings[$name] = $string;
                    $string = "";
                }
                $name = trim(substr($line,$stop + 1));
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
