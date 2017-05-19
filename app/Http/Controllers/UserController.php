<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 14:53
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        //var_dump(1);exit;
        $members = DB::table('members')
            //->where(['username'=>'admin'])
            ->offset(5)
            ->limit(5)
            ->get();
            //->first();
            //->value('username');
            //->pluck('username');
            ///->chunk(2);
            //->count();
            //->max('credit1');
        //$members = DB::select("select * from example_members");
        //var_dump($members);exit;
        return view('user', [
            'title'     => 'User',
            'members'   => $members
        ]);
    }


}
