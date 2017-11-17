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
use App\Models\ShoppingCart;
use App\Models\SessionWxs;

class ShopController extends Controller
{
    private $weid;
    private $appIDS;
    private $appSecretS;
    private $openid;

    public function __construct(Request $request)
    {
        //$this->middleware('auth:api');
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
    public function wxLogin(Request $request, $from = null)
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
            $session = json_decode((string) $response->getBody(), true);

            // session 存储 session_key + openid
            $key = thirdSession(128);
            //session([$key => $session['session_key'].'+'.$session['openid'].'+'.strtotime('+2 hour')]);

            $filter = ['openid' => $session['openid']];
            $session['session_3rd_key'] = $key;
            $session['expires_in'] = strtotime('+2 hour');
            //return response()->json(['session'=>$session]);

            $sess = SessionWxs::updateOrCreate($filter, $session);
            //return response()->json(['session_key'=>$key, 'insert'=>$sess]);/**/

            if ($from == 'inside') {
                return $session;
            } else {
                return response()->json(['session_key'=>$key]); // , 'session'=>$sess, 'a'=>session($key)
            }

        } else {
            return response()->json(['code'=>1, 'message'=>'Invalid code']);
        }
    }


    /**
     * 微信小程序 支付接口 测试
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        $code       = $request->input('code');
        $key        = $request->input('session_key');
        $session    = SessionWxs::where(['session_3rd_key'=>$key])->first();

        if (empty($code)) {
            return response()->json(['code'=>1, 'message'=>'Invalid code']);
        }

        if ($session['expires_in'] - time() < 0) { // 过期
            $session = $this->wxLogin($request, 'inside');
        }

        // 下单操作 生成订单号
        $package = $this->doOrders($request, $session['openid']);
        return redirect()->route('pay.wxspay', $package);

    }


    /**
     * 处理订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doOrders(Request $request, $openid) //
    {
        /*return response()->json(['abc'=>123]);
        $key = $request->input('session_key');
        $session = session($key);
        //$session = SessionWxs::where(['session_3rd_key'=>$key])->first();
        return response()->json(['session_key'=>$key, 'session_val'=>$session]);

        $total = $request->input('total');
        //$sessionKey = explode('+', $sessionKey);
        $package    = [
            'weid'          => $this->weid,
            'body'          => 123,
            'out_trade_no'  => 'oadx'.'-'.date('ymdHis', time()).'-'.ltrim(explode(' ', microtime())[0],'0.'),
            'total_fee'     => floatval($total),
        ];
        return redirect()->route('api.shop.pay', $package);
        return response()->json(['session_key'=>$key, 'session_val'=>123]);

        return ['orderId'=>$request->input('orderId')];*/

        $orderId    = $request->input('orderId');
        $goods      = $request->input('goods');
        $goodsPrice = 0;
        $dispatchPrice = 0;
        foreach ($goods as $good) {
            $goodsPrice += $good['productprice'] * $good['num'];
        }
        $ordersn    = 'oadx'.'-'.date('ymdHis', time()).'-'.ltrim(explode(' ', microtime())[0],'0.');


        if (empty($orderId)) {
            // 提交订单操作
            if ($request->input('op') == 'placeAnOrder') {

                // 下订单
                //$ordersn    = 'oadx'.'-'.date('ymdHis', time()).'-'.ltrim(explode(' ', microtime())[0],'0.');
                $orderData  = [
                    'weid'      => $this->weid,
                    'from_user' => $openid,
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

                // 下详单商品
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
                //dd(2, $orderData, $orderGoodsData, $goods, $request->input(), csrf_token());
                //return redirect()->route('api.shop.pay', $package);
                //return response()->json($package); //$request->input()
            }
        } else {
            // 更新支付订单号
            ShoppingOrder::where(['id'=>$orderId])->update(['ordersn'=>$ordersn]);
        }

        // 准备数据 前往支付
        $package    = [
            'weid'          => $this->weid,
            'openid'        => $openid,
            'body'          => $goods[0]['title'],
            'out_trade_no'  => $ordersn, //$orderId, //
            'total_fee'     => floatval($goodsPrice+$dispatchPrice),
        ];

        return $package;

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
     * 获取购物车商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart(Request $request)
    {
        $openId = $request->input('openid');
        $this->openid = 'odk8d0vQ3Oqr7UAOOPFaGxCuOG0E';
        $carts = ShoppingCart::where(['weid'=>$this->weid, 'from_user'=>$this->openid])->get(); //
        foreach ($carts as $i=>$cart) {
            $carts[$i]['good'] = ShoppingGoods::where(['id'=>$cart['goodsid']])->first();
        }

        return response()->json($carts);
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
     * 商品详情  (列表详情)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsDetail(Request $request)
    {
        $ids = $request->input('ids');
        $nums = $request->input('nums');
        $idsArr = explode(',', $ids);
        $numsArr = explode(',', $nums);
        $total = 0;
        //$dispatch = 0;

        $goods = ShoppingGoods::where(['weid'=>$this->weid]) //, 'id'=>$id
            ->whereIn('id', $idsArr)
            ->orderBy(DB::raw('field(id,'.$ids.')'))->get();

        for ($i = 0; $i < count($numsArr); $i++) {
            $goods[$i]['num'] = $numsArr[$i];
            $total += $goods[$i]['productprice'] * $numsArr[$i];
            //$dispatch += $goods[$i]['dispatch'];
        }
        //$detail['thumb_url1'] = iunserializer($detail['thumb_url']);

        //修正图片url
        //$i = 0;
        foreach ($goods as $i=>$detail) {
            $thumb_url[$i] = iunserializer($detail['thumb_url']);
            //$j = 0;
            foreach ($thumb_url[$i] as $j=>$thumb) {
                $thumb_url[$i][$j]['attachment'] = str_replace('/6/', '/', $thumb_url[$i][$j]['attachment']);
                //$j++;
            }
            $goods[$i]['thumb_url1'] = $thumb_url[$i];
            //$i++;
        }

        return response()->json(['goods'=>$goods, 'total'=>$total]);
    }

}
