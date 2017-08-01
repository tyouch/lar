<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
//use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use App\Lib\Wechat\HttpRequest;
use App\Models\Account;
use App\Models\Fans;

class WechatAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $path)
    {
        $appID          = config('wechat.appID');
        $appSecret      = config('wechat.appSecret');
        $redirectUri    = config('wechat.redirectUri').$path;

        //dd($request->input('code') && $request->session()->get('openid'));
        $openid = session('openid') ? session('openid') : null;
        //dd($openid, session('openid'));

        if(empty($openid)){

            //第一步：用户同意授权，获取code
            $code = $request->input('code') ? $request->input('code') : null;
            if (empty($code)) {
                $authorize_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appID.'&redirect_uri='.urlencode($redirectUri).'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
                //dd($authorize_url);
                return redirect($authorize_url);
                exit;
            }

            // 第二步：通过code换取网页授权access_token
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appID.'&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code';
            $token_openid = HttpRequest::toArray($get_token_url);
            //dd($token_openid, $code, $get_token_url);

            // 第三步：刷新access_token（如果需要）

            // 第四步：拉取用户信息(需scope为 snsapi_userinfo)
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token_openid['access_token'].'&openid='.$token_openid['openid'].'&lang=zh_CN';
            $user_info = HttpRequest::toArray($get_user_info_url);
            $user_info = array_merge($token_openid, $user_info); //dd($user_info);
            session($user_info);
        }else{
            $user_info = $request->session()->all();
        }

        cookie('nickname', session('nickname'), 1800);
        cookie('openid', session('openid'), 1800);
        //dd($user_info, cookie('nickname', session('nickname'), 1800));

        // 判断用户表中是否有该用户
        $fans = Fans::where(['from_user'=>session('openid')])->first();
        //$account = Account::where(['openid'=>session('openid')])->first();
        //dd($user_info, session('openid'),$account->openid, $account);
        //var_dump($user_info, $account);exit;

        //没有则保存
        if (empty($fans)){ // $account insert
            $post = new Fans; //
            $post->from_user        = $user_info['openid'];
            $post->nickname         = $user_info['nickname'];
            $post->avatar           = $user_info['headimgurl'];
            $post->gender           = $user_info['sex'];
            $post->nationality      = $user_info['country'];
            $post->resideprovince   = $user_info['province'];
            $post->residecity       = $user_info['city'];
            $post->createtime       = time();
            $post->salt             = random(8);
            $post->weid             = 11;
            $post->bio              = '';
            $post->interest         = '';
            //dd($post);

            $post->save();
            $weid = $post->weid;
            //dd('Insert ok');
        }else{
            if($fans->nickname != urlencode($user_info['nickname']) || $fans->headimgurl != $user_info['headimgurl']){
                $fans->nickname     = $user_info['nickname'];
                $fans->avatar       = $user_info['headimgurl'];
                //dd($fans);

                $fans->save();
                $weid = $fans->weid;
                //dd('Update ok');
            }
            //cookie('if_register', $account['if_register'], 1800);
        }
        session(['weid'=>$weid]);
        return $next($request);
    }
}
