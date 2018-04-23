<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JupyterController extends Controller
{
    public function notebook(){
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://localhost:8888/');
        
        echo '<div>';
        $body = $res->getBody();
        $da = (array) json_decode($body);

        echo '<pre>';
        echo print_r($da);
        echo '</pre>';
        
        echo '</div>';
    }
}
