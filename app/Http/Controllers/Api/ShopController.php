<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShoppingAdv;
use App\Models\ShoppingGoods;

class ShopController extends Controller
{
    private $weid;
    private $appIDS;
    private $appSecretS;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
        $this->weid = $request->input('weid');
        $this->appIDS       = config('wechat.appIDS');
        $this->appSecretS   = config('wechat.appSecretS');
        //wx3aed9fe20f883ac8 / d2faf1607c212876a1f123af200d501b
    }

    /**
     * 微信小程序 支付接口 测试
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        $code = $request->input('code');
        if(!empty($code)){

            //$loginUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';

            // 通过code 获取 openid
            $http   = new Client();
            $response = $http->get('https://api.weixin.qq.com/sns/jscode2session', [
                'query' => [
                    'appid'     => $this->appIDS,
                    'secret'    => $this->appSecretS,
                    'js_code'   => $code,
                    'grant_type' => 'authorization_code',
                ]
            ]);
            $loginInfo = json_decode((string) $response->getBody(), true);
            //dump($accessToken);
            //return response()->json($loginInfo);

            // 组织包准备支付
            $package = [
                'weid'          => $this->weid,
                'body'          => $request->input('body'),
                'out_trade_no'  => $request->input('out_trade_no'),
                'total_fee'     => $request->input('total_fee'),
                'openid'        => $loginInfo['openid']
            ];
            //return response()->json($package);
            //return response()->json(['loginInfo'=>$loginInfo]);
            return redirect()->route('pay.wxspay', $package);

        }else{
            return response()->json(['abc'=>1]);
        }
    }

    /**
     * 主页广告
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndexAdds(Request $request)
    {
        //$path = array_reverse(explode('/', $request->path())); //dd($path);
        //$this->weid = $path[0];
        $advs = ShoppingAdv::where(['weid'=>$this->weid])->get(); //dd($advs);
        $data = []; $i = 0;
        foreach ($advs as $adv) {
            $data[$i] = $adv;
            $data[$i++]['thumb'] = url($adv->thumb);
        }
        return response()->json($data);
    }

    /**
     * 主页商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndexGoods(Request $request)
    {
        $goods = ShoppingGoods::where(['weid'=>$this->weid, 'isrecommand'=>1])->get(); //dd($goods);
        $data = []; $i=0;
        foreach ($goods as $good) {
            $data[$i] = $good;
            $data[$i++]['thumb_url'] = iunserializer($good['thumb_url']);
        }
        return response()->json($goods);
    }

    /**
     * 商品详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsDetail(Request $request)
    {
        $id = $request->input('id');
        $detail = ShoppingGoods::where(['weid'=>$this->weid, 'id'=>$id])->first();
        //$detail['thumb_url1'] = iunserializer($detail['thumb_url']);
        $thumb_url = iunserializer($detail['thumb_url']);
        $i = 0;
        foreach ($thumb_url as $thumb) {
            $thumb_url[$i]['attachment'] = str_replace('/6/', '/', $thumb_url[$i]['attachment']);
            $i++;
        }
        $detail['thumb_url1'] = $thumb_url;

        return response()->json($detail);
    }

}
