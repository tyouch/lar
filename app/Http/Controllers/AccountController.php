<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Lib\Wechat\HttpRequest;
use App\Models\Wechats;
use App\Models\Rule;
use App\Models\RuleKeyword;
use App\Models\BasicReply;
use App\Models\NewsReply;


class AccountController extends Controller
{
    private $tokenUrl;
    private $ipListUrl;
    private $menuQueryUrl;
    private $menuCreateUrl;
    private $redirectUri;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->tokenUrl     = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
        $this->ipListUrl    = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=';
        $this->menuQueryUrl = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=';
        $this->menuCreateUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';
        $this->redirectUri = config('wechat.redirectUri');

        //
        /*$weid = $request->input('weid');
        $account = Wechats::where(['weid'=>$weid])->first();
        $payment = iunserializer($account->payment);
        $this->appId = $payment['wechat']['appid'];
        $this->mchID = $payment['wechat']['mchid'];*/
    }

    /**
     * 公众号管理首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * 检查 access_token
     * @param $weid
     * @return mixed
     */
    public function checkToken($weid)
    {
        $acctount = Wechats::where(['weid'=>$weid])->first(); //dump($acctount);
        $tokenArr = unserialize($acctount['access_token']); //dump($tokenArr);

        //dump($tokenArr['expire']-time());
        if(empty($tokenArr['access_token']) || $tokenArr['expire']-time() < 0) {
            $this->tokenUrl .= 'appid='.$acctount['key'].'&secret='.$acctount['secret'];
            $resArr = HttpRequest::toArray($this->tokenUrl); //dump($resArr);
            !empty($resArr['errcode']) && dump($resArr['errmsg']);

            $acctount->access_token = serialize([
                'token'     => $resArr['access_token'],
                'expire'    => time() + $resArr['expires_in'],
            ]);
            $acctount->save();
            //dump(2);
            //... ...
            session(['token'=>$tokenArr['token']]);
            //dd(session('token'));
        }

        return $acctount;
    }

    /**
     * 公众号管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manage(Request $request)
    {
        $begin = microtime(true);
        $weid = $request->input('weid');
        $account = $this->checkToken($weid);

        $info = [
            'token'     => session('token'),
            'apiUrl'    => $account['hash'],

        ];

        $this->ipListUrl .= session('token');
        //$res = HttpRequest::content($this->wxIpListUrl); dump(session('token'), $res);
        //$this->menuQueryUrl .= $tokenArr['token'];
        //$res = HttpRequest::content($this->menuQueryUrl); dump(session('token'), $res);

        $end    = microtime(true);
        return view('wechat.manage', [
            'pass'      => $end - $begin,
            'weid'      => $weid,
            'account'   => $account,
            'token'     => session('token'),
            'apiAddress'=> $this->redirectUri.'api?hash='.$account['hash']
        ]);
    }

    /**
     * 展示和编辑规则 -----------------------------------------
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageRule(Request $request)
    {
        $begin  = microtime(true);
        $weid   = $request->input('weid');
        $module = $request->input('module');
        $_token = $request->input('_token');


        if (isset($_token) && $_token == csrf_token()) {

            $this->validate($request, [
                'name'          => 'required|max:20',
                'keyword'       => 'required',
                'reply'         => 'required',
                'status'        => 'required',
                'displayOrder'  => 'required',
            ]);

            if ($module == 'news') {
                $this->validate($request, [
                    'title'         => 'required',
                    'description'   => 'required',
                    //'thumb'         => 'required',
                    'url'           => 'url'
                ]);
            }

            $rid = $request->input('id');
            //dd($request->input('id'));

            if (empty($rid)) {
                $rule           = new Rule();
                $keyword        = new RuleKeyword();
                ($module == 'basic')    && $reply = new BasicReply();
                ($module == 'news')     && $reply = new NewsReply();
            } else {
                $rule = Rule::with('keyword', 'basicReply')->where([
                    'weid'      => $weid,
                    'module'    => $module,
                    'id'        => $rid])->first();
                $keyword        = RuleKeyword::where(['rid'=>$rid])->first();
                ($module == 'basic')    && $reply = BasicReply::where(['rid'=>$rid])->first();
                ($module == 'news')     && $reply = NewsReply::where(['rid'=>$rid])->first();
            }

            $rule->name         = $request->input('name');
            $rule->weid         = $weid;
            $rule->status       = $request->input('status');
            $rule->displayorder = $request->input('displayOrder');
            ($module == 'basic')    && $rule->module = 'basic';
            ($module == 'news')     && $rule->module = 'news';
            $rule->save();
            $rid = $rule->id;

            $keyword->rid       = $rid;
            $keyword->content   = $request->input('keyword');
            $keyword->weid      = $weid;
            ($module == 'basic')    && $keyword->module = 'basic';
            ($module == 'news')     && $keyword->module = 'news';
            $keyword->save();

            $reply->rid         = $rid;
            $reply->content     = $request->input('reply');
            if ($module == 'news') {
                $thumb = $request->file('thumb') ? $this->uploadFile($request->file('thumb'), random(10), 'imgs/uploads/images/'.date('Y').'/'.date('m').'/') : null;
                $reply->title       = $request->input('title');
                $reply->description = $request->input('description');
                !empty($thumb)      && $reply->thumb = $thumb;
                $reply->url         = $request->input('url');
            }
            $reply->save();

            return redirect()->route('account.rule', ['weid'=>$weid, 'module'=>$module]);


            /*if ($module == 'basic') {
                $this->validate($request, [
                    'name'          => 'required|max:20',
                    'keyword'       => 'required',
                    'basicReply'    => 'required'
                ]);
                $rid = $request->input('id');
                $rule = Rule::with('keyword', 'basicReply')->where([
                    'weid'          => $weid,
                    'module'        => 'basic',
                    'id'            => $rid])->first();
                //var_dump($rule,$weid,$rid);exit;// $rule['name'].'|'.$request->input('name'), $rule['keyword'][0]['content'].'|'.$request->input('keyword'), $rule['basicReply'][0]['content'].'|'.$request->input('basicReply'));
                if(empty($rule)) {
                    $rule           = new Rule();
                    $keyword        = new RuleKeyword();
                    $basicReply     = new BasicReply();
                } else {
                    $keyword        = RuleKeyword::where(['rid'=>$rid])->first();
                    $basicReply     = BasicReply::where(['rid'=>$rid])->first();
                }

                $rule->name         = $request->input('name');
                $rule->weid         = $weid;
                $rule->module       = 'basic';
                $rule->save();
                $rid = $rule->id;

                $keyword->rid       = $rid;
                $keyword->content   = $request->input('keyword');
                $keyword->weid      = $weid;
                $keyword->module    = 'basic';
                $keyword->save();

                $basicReply->rid    = $rid;
                $basicReply->content= $request->input('basicReply');
                $basicReply->save();

                return redirect()->route('account.rule', ['weid'=>$weid, 'module'=>'basic']);
            }*/

        }

        switch ($module) {
            case 'basic':
                $rules = Rule::with('keyword', 'basicReply')
                    ->where(['weid' => $weid, 'module' => 'basic'])
                    ->orderBy('displayorder', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'news':
                $rules = Rule::with('keyword', 'newsReply')->where(['weid' => $weid, 'module' => 'news'])
                    ->orderBy('displayorder', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
                break;
            case 'image':
                break;
            case 'audio':
                break;
            case 'video':
                break;
            default:
        }


        $end    = microtime(true);
        return view('wechat.rule', [
            'pass'      => $end - $begin,
            'weid'      => $weid,
            'rules'     => $rules,
            'module'    => $module
        ]);
    }

    /**
     * 自定义菜单管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageMenu(Request $request)
    {
        $begin = microtime(true);
        $weid = $request->input('weid');
        $account = $this->checkToken($weid);
        //dd($account['menuset']);
        $_token = $request->input('_token'); //dd($_token);

        if(empty($account['menuset'])) { // db menuset is null or request flush
            $this->menuQueryUrl .= session('token');//$tokenArr['token'];
            $menuSet = HttpRequest::toArray($this->menuQueryUrl); //dump(session('token'), $menuSet);
            //dd($menuSet);
            $account['menuset'] = base64_encode(serialize($menuSet));
            $account->save();

        } elseif(isset($_token) && $_token == csrf_token()) { // 提交编辑

            $buttonPost = $request->input('button');
            $button = []; $i=0;
            foreach ($buttonPost as $btn) {
                $button[$i]['name'] = urlencode($btn['name']);
                $button[$i]['type'] = urlencode($btn['type']);
                $button[$i]['url'] = urlencode($btn['url']);

                if(!empty($btn['sub_button'])) {
                    $j = 0;
                    foreach ($btn['sub_button'] as $sBtn) {
                        $button[$i]['sub_button'][$j]['name'] = urlencode($sBtn['name']);
                        $button[$i]['sub_button'][$j]['type'] = urlencode($sBtn['type']);
                        $button[$i]['sub_button'][$j++]['url'] = urlencode($sBtn['url']);
                    }
                }
                $i++;
            }
            $menuSet['menu'] = [
                'button'        => $button,
                'createtime'    => time()
            ]; //dd($buttonPost, $menuSet);
            $account['menuset'] = base64_encode(serialize($menuSet));
            $account->save();

            $json = json_encode(['button'=>$buttonPost], JSON_UNESCAPED_UNICODE); //dump($json);
            $this->menuCreateUrl .= session('token'); //dump($this->menuCreateUrl);
            $resArr = HttpRequest::toArray($this->menuCreateUrl, $json);
            !empty($resArr['errcode']) && dump($resArr);
        }

        //调取渲染
        $menuSet1 = unserialize(base64_decode($account['menuset'])); //dump($menuSet1);
        $menuSet = []; $i=0;
        foreach ($menuSet1['menu']['button'] as $btn) {
            $menuSet['menu']['button'][$i]['name'] = urldecode($btn['name']);
            $menuSet['menu']['button'][$i]['type'] = urldecode($btn['type']);
            $menuSet['menu']['button'][$i]['url'] = urldecode($btn['url']);
            if(!empty($btn['sub_button'])) {
                $j = 0;
                foreach ($btn['sub_button'] as $sBtn) {
                    $menuSet['menu']['button'][$i]['sub_button'][$j]['name'] = urldecode($sBtn['name']);
                    $menuSet['menu']['button'][$i]['sub_button'][$j]['type'] = urldecode($sBtn['type']);
                    $menuSet['menu']['button'][$i]['sub_button'][$j++]['url'] = urldecode($sBtn['url']);
                }
            }
            $i++;
        }
        //$menuSet['menu']['createtime'] = $menuSet1['createtime'];
        //dd($menuSet1, $menuSet);


        $end    = microtime(true);
        return view('wechat.menu', [
            'pass'      => $end - $begin,
            'weid'      => $weid,
            'button'    => $menuSet['menu']['button'],
            'module'    => 'menu'
        ]);
    }

    /**
     * 支付参数
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payment(Request $request)
    {
        $begin  = microtime(true);
        $weid   = $request->input('weid');
        $_token = $request->input('_token');

        $account = Wechats::where(['weid'=>$weid])->first();
        $payment = iunserializer($account->payment); //dd($payment);

        if(isset($_token) && $_token == csrf_token()) {

            $wechat = $request->input('wechat');
            $this->validate($request, [
                'wechat.appid' => 'required',
                'wechat.secret' => 'required',
                'wechat.mchid' => 'required',
                'wechat.signkey' => 'required',
                'wechat.switch' => 'required'
            ]);
            $account->payment = serialize(['wechat'=>$wechat]);
            $account->save();
            return redirect()->route('account.payment', ['weid'=>$weid]);
        }

        $end    = microtime(true);
        return view('wechat.payment', [
            'pass'      => $end - $begin,
            'weid'      => $weid,
            'module'    => 'payment',
            'wechat'    => @$payment['wechat']
        ]);
    }

    /**
     * 编辑 和删除 操作
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function operate(Request $request)
    {
        $weid = $request->input('id');
        $account = Wechats::where(['weid'=>$weid])->first();
        if ($request->input('op') == 'edit') {
            return response()->json($account);
        }
        if ($request->input('op') == 'del') {
            //$account->delete();
            //@unlink('imgs/uploads/qrcode_'.$weid.'.jpg');
            //@unlink('imgs/uploads/headimg_'.$weid.'.jpg');
            return redirect('account');
        }
    }

    /**
     * 插入 和更新 操作
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
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
        $account->lastupdate= time();


        //dd($account);
        $account->save();

        $id = $account->weid;
        //dd($account->weid);
        $request->file('qrcode') &&
            $this->uploadFile($request->file('qrcode'), 'qrcode_'.$id, 'imgs/uploads/');

        $request->file('headimg') &&
            $this->uploadFile($request->file('headimg'), 'headimg_'.$id, 'imgs/uploads/');

        return redirect('account');
    }

    /**
     * 上传 头像和 二维码
     * @param $file
     * @param $name
     * @return string
     */
    public function uploadFile($file, $name, $destPath){

        /*$data = [
            'fileName'      => $file->getClientOriginalName(),
            'fileExt'       => $file->getClientOriginalExtension(),
            'fileRealPath'  => $file->getRealPath(),
            'fileSize'      => $file->getSize(),
            'fileMimeType'  => $file->getMimeType(),

        ];*/
        //dd($data);
        //Move Uploaded File
        //$destPath = 'imgs/uploads/';
        $fileName = $name.'.jpg';
        !file_exists($destPath) && mkdir($destPath,0755,true);
        file_exists($fileName)  && unlink($fileName);
        $file->move($destPath, $fileName); // $file->getClientOriginalName()
        //$file->store($destPath, $name.'.'.$file->getClientOriginalExtension());
        //dd($destPath);
        return $destPath.$fileName;
    }

}
