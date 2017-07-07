<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $begin = microtime(true);
        $end    = microtime(true);
        return view('wechat.index', [
            'pass'          => $end - $begin
        ]);
    }

    public function uploadFile(Request $request){

        //dd($request->file());
        return response()->json($_FILES);
        return response()->json($request->file('myfile'));
        //return response()->json(['abc'=>123]);
        $file = $request->file('file');

        $data = [
            'fileName'      => $file->getClientOriginalName(),
            'fileExt'       => $file->getClientOriginalExtension(),
            'fileRealPath'  => $file->getRealPath(),
            'fileSize'      => $file->getSize(),
            'fileMimeType'  => $file->getMimeType(),

        ];

        //Move Uploaded File
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());

        return response()->json($data);
    }
}
