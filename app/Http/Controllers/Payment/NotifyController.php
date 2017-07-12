<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotifyController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $file = public_path('js/cb.json');
        $raw_post_data = file_get_contents('php://input', 'r');
        //dump($raw_post_data, empty($raw_post_data));

        if(file_exists($file) && empty($raw_post_data)) {
            $msg = file_get_contents($file);
            unlink($file);//file_put_contents($file, null, LOCK_EX);
            dd($msg);
        } else {
            //$obj = simplexml_load_string($raw_post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
            //file_put_contents($file, json_encode($obj, JSON_UNESCAPED_UNICODE), LOCK_EX);
            file_put_contents($file, $raw_post_data, LOCK_EX);
        }
    }
}
