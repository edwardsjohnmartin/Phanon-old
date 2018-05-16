<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class TestController extends Controller{
    public function index(){
        $arr = [];
        for ($i = 0; $i < 10; $i++){
            $arr[$i] = new User();
            $arr[$i]->name = "User ".$i;
        }
        return view("test.test", ["users" => $arr]);
    }
}
?>
