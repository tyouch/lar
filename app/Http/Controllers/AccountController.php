<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wechats;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $begin = microtime(true);

        //dd(public_path('js/cb.json'));

        $account = Wechats::get();
        //dd($account);

        $end    = microtime(true);
        return view('wechat.index', [
            'pass'      => $end - $begin,
            'account'   => $account
        ]);
    }

    public function operate(Request $request)
    {
        $weid = $request->input('id');
        $account = Wechats::where(['weid'=>$weid])->first();
        if ($request->input('op') == 'edit') {
            return response()->json($account);
        }
        if ($request->input('op') == 'del') {
            $account->delete();
            @unlink('imgs/uploads/qrcode_'.$weid.'.jpg');
            @unlink('imgs/uploads/headimg_'.$weid.'.jpg');
            return redirect('account');
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',//unique:posts|
            'type' => 'required',
            'level' => 'required',
            'key' => 'required',
            'secret' => 'required',
            'account' => 'required',
            'original' => 'required',
        ]);
        //dd($request->file(), $request);

        $weid = $request->input('weid');
        if(empty($weid)) {
            $account = new Wechats();
            $account->hash              = random(5);
            $account->token             = random(32);
            $account->EncodingAESKey    = random(43);

            $account->uid       = 1;//
            $account->parentid  = 0;//
            $account->signature = '';
            $account->country   = '';
            $account->province  = '';
            $account->city      = '';
            $account->username  = '';
            $account->password  = '';
            $account->welcome   = '';
            $account->default   = '';
            $account->lastupdate        = '0';
            $account->default_period    = '0';
            $account->styleid   = 1;

            $account->menuset       = '';
            $account->jsapi_ticket  = '';
        } else {
            $account = Wechats::where(['weid'=>$weid])->first();
        }

        $account->name      = $request->input('name');
        $account->type      = $request->input('type');
        $account->level     = $request->input('level');
        $account->key       = $request->input('key');
        $account->secret    = $request->input('secret');
        $account->account   = $request->input('account');
        $account->original  = $request->input('original');

        //dd($account);
        $account->save();

        $id = $account->weid;
        //dd($account->weid);
        $request->file('qrcode') &&
            $this->uploadFile($request->file('qrcode'), 'qrcode_'.$id);

        $request->file('headimg') &&
            $this->uploadFile($request->file('headimg'), 'headimg_'.$id);
        return redirect('account');
    }

    public function uploadFile($file, $name){

        /*$data = [
            'fileName'      => $file->getClientOriginalName(),
            'fileExt'       => $file->getClientOriginalExtension(),
            'fileRealPath'  => $file->getRealPath(),
            'fileSize'      => $file->getSize(),
            'fileMimeType'  => $file->getMimeType(),

        ];*/
        //dd($data);
        //Move Uploaded File
        $destPath = 'imgs/uploads/';
        $fileName = $name.'.jpg';
        !file_exists($destPath) &&
            mkdir($destPath,0755,true);
        file_exists($fileName) &&
            unlink($fileName);
        $file->move($destPath, $fileName); // $file->getClientOriginalName()
        //$file->store($destPath, $name.'.'.$file->getClientOriginalExtension());
        //dd($destPath);
        return $destPath.$fileName;
    }

}
