<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use App\Lesson;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Importer;
class ImportController extends Controller{
    public function index(){
        return view("import.index");
    }
    /**
     * Handle file upload
     * @param Request $request File submitted byt the user.
     * @return \Illuminate\Contracts\View\Factory|Illuminate\View\View
     */
    public function upload(Request $request){
        $lessonsToShow = [];

        if($request->hasFile("newLesson")){
            // file was included
            $file = $request->file('newLesson');

            $userID = Auth::user() != null ? Auth::user()->id : "-1";
            // create unique name
            $newName = time()."_".$userID."_".$file->getClientOriginalName();

            $savePath = public_path()."/".config("app.upload_folder");
            
            // move to temp folder.
            $file->move($savePath,$newName);

            // read filelines
            $fileContent = file($savePath."/".$newName);
            $fileString = "";
            // iterate each line
            foreach($fileContent as $line){
                $fileString.=$line;
            }

            $conceptsToShow = Importer::get_concepts($fileString);

            // if storing in Temp
            // https://stackoverflow.com/questions/45619248/laravel-5-4-fopen-filename-cannot-be-empty
            //$savePath = $file->storeAs(config("app.upload_folder"),$newName);
            //echo "path:".$savePath;


        }
        return view("import.reader", ['concepts' => $conceptsToShow]);
    }
}
?>
