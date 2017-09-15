<?php

namespace App\Http\Controllers\Api;

use App\Models\ShoppingInvoice;
use GuzzleHttp\Client;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ShoppingAdv;
use App\Models\ShoppingGoods;
use App\Models\ShoppingAddress;
use App\Models\ShoppingOrder;
use App\Models\ShoppingOrderGoods;

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
     * 小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function wxLogin(Request $request)
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

            // session 存储 session_key + openid
            $key = thirdSession(128);
            session([$key => $loginInfo['session_key'].'+'.$loginInfo['openid'].'+'.strtotime('+2 hour')]);
            session(['abc'=>123]);

            return response()->json(['session_key'=>$key]); //, 'a'=>session($key)
            //return redirect()->route('pay.wxspay', $package);

        }else{
            return response()->json(['code'=>1, 'message'=>'Invalid code']);
        }
    }

    /**
     * 处理订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders(Request $request)
    {
        $key = $request->input('session_key');
        //$sessionKey = explode('+', $sessionKey);
        return response()->json(['session_key'=>$key, 'session_val'=>session('abc')]);


        // 提交订单操作
        if ($request->input('op') == 'placeAnOrder') {

            $goods      = $request->input('goods');
            $goodsPrice = $dispatchPrice = 0;
            foreach ($goods as $good) {
                $goodsPrice += $good['productprice'];
            }

            // 订单
            $ordersn    = 'oadx'.'-'.date('ymdHis', time()).'-'.ltrim(explode(' ', microtime())[0],'0.');
            $orderData  = [
                'weid'      => $this->weid,
                'from_user' => 'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E',
                'addressid' => $request->input('addressid'),
                'invoiceId' => $request->input('invoiceid'),
                'ordersn'   => $ordersn,
                'status'    => 1,
                'createtime'    => time(),
                'updatetime'    => time(),
                'goodsprice'    => $goodsPrice,
                'dispatchprice' => $dispatchPrice,
                'price'         => $goodsPrice + $dispatchPrice
            ];
            $order = ShoppingOrder::create($orderData);
            $orderId = $order['id'];
            //$orderId = 'xxxxx';

            // 订单商品
            foreach ($goods as $good) {
                $orderGoodsData = [
                    'weid'      => $this->weid,
                    'orderid'   => $orderId,
                    'goodsid'   => $good['id'],
                    'total'     => $good['num'],
                    'price'     => $good['productprice'],
                    'createtime'=> time(),
                ];
                $orderGoods = ShoppingOrderGoods::create($orderGoodsData);
            }
            //dd(2, $orderData, $orderGoodsData, $goods, $request->input(), csrf_token()); //

            // 准备数据 前往支付
            $package    = [
                'weid'          => $this->weid,
                'body'          => $goods[0]['title'],
                'out_trade_no'  => $orderId, //$order['ordersn'],
                'total_fee'     => floatval($goodsPrice),
            ];
            return redirect()->route('api.shop.pay', $package);
            //return response()->json($package); //$request->input()
        }

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
                //'weid'          => $this->weid,
                'body'          => $request->input('body'),
                'out_trade_no'  => $request->input('out_trade_no'),
                'total_fee'     => $request->input('total_fee'),
                'openid'        => $loginInfo['openid']
            ];
            //return response()->json($package);
            //return response()->json(['loginInfo'=>$loginInfo]);
            return redirect()->route('pay.wxspay', $package);

        }else{
            return response()->json(['code'=>1, 'message'=>'Invalid code']);
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
     * 获取收货地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddress(Request $request)
    {
        $openId = $request->input('openid');
        $address = ShoppingAddress::where(['weid'=>$this->weid, 'isdefault'=>1])->first(); //, 'openid'=>$openId

        return response()->json($address);
    }

    /**
     * 获取发票信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoice(Request $request)
    {
        $openId = $request->input('openid');
        $invoice = ShoppingInvoice::where(['weid'=>$this->weid, 'isdefault'=>1])->first(); //, 'openid'=>$openId

        return response()->json($invoice);
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
        $ids = $request->input('ids');
        $nums = $request->input('nums');
        $idsArr = explode(',', $ids);
        $numsArr = explode(',', $nums);

        $goods = ShoppingGoods::where(['weid'=>$this->weid]) //, 'id'=>$id
            ->whereIn('id', $idsArr)
            ->orderBy(DB::raw('field(id,'.$ids.')'))->get();

        for ($i = 0; $i < count($numsArr); $i++) {
            $goods[$i]['num'] = $numsArr[$i];
        }
        //$detail['thumb_url1'] = iunserializer($detail['thumb_url']);

        $i = 0;
        foreach ($goods as $detail) {
            $thumb_url[$i] = iunserializer($detail['thumb_url']);
            $j = 0;
            foreach ($thumb_url[$i] as $thumb) {
                $thumb_url[$i][$j]['attachment'] = str_replace('/6/', '/', $thumb_url[$i][$j]['attachment']);
                $j++;
            }
            $goods[$i]['thumb_url1'] = $thumb_url[$i];
            $i++;
        }

        return response()->json($goods);
    }

}
