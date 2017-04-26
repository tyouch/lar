<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 14:53
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index(){

        //var_dump(1);exit;
        return view('user', [
            'title' => 'User',
        ]);
    }


}
