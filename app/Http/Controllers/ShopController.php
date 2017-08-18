<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
//use Stevenyangecho\UEditor\UEditorServiceProvider as UEditor;

use App\Lib\Wechat\HttpRequest;
use App\Models\ShoppingCategory;
use App\Models\ShoppingGoods;
use App\Models\ShoppingOrder;
use App\Models\ShoppingAdv;
use App\Models\ShoppingOrderGoods;


class ShopController extends Controller
{
    private $weid;
    private $fileName;
    private $destPath;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->weid = $request->input('weid');
        $this->fileName = random(10);
        $this->destPath = 'images/uploads/'.date('Y').'/'.date('m').'/';
    }

    public function index()
    {
        $begin = microtime(true);


        $end    = microtime(true);
        return view('web.shop.index', [
            'pass'      => $end - $begin,
            'weid'  => $this->weid
        ]);
    }

    /**
     * 商品分类管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function category(Request $request)
    {
        $begin = microtime(true);
        parse_str($request->getQueryString(), $pagePram); //dump($pagePram);

        $_token = $request->input('_token'); //dump($_token, csrf_token());
        if (isset($_token) && $_token == csrf_token()) {

            $id = $request->input('id');
            $category = ShoppingCategory::where(['weid' => $this->weid, 'id' => $id])->first();

            if($request->ajax()){

                // 编辑回填
                if($request->input('op') == 'edit'){
                    return response()->json($category);
                }

                // 删除
                if($request->input('op') == 'del'){
                    $code1 = $code2 = 0;
                    if ($request->input('pid') == 0) {
                        $code1 = ShoppingCategory::where(['weid' => $this->weid, 'parentid' => $id])->delete();
                    }
                    //$code2 = ShoppingCategory::where(['weid' => $this->weid, 'id' => $id])->delete();
                    $code2 = $category->delete();
                    return response()->json(['code1' => $code1, 'code2' => $code2]);
                }

            }

            // 验证输入
            $this->validate($request, [
                'name'          => 'required|max:20',
                'description'   => 'required',
                'displayorder'  => 'required',
                'enabled'       => 'required',
                'isrecommand'   => 'required'
            ]);

            // 添加
            if (empty($category)) {
                $category = new ShoppingCategory();
            }

            $thumb = $request->file('thumb') ? $this->uploadFile($request->file('thumb'), $this->fileName, $this->destPath) : null;
            $parentid = $request->input('parentid');

            $category->weid         = $this->weid;
            $category->name         = $request->input('name');
            $category->description  = $request->input('description');
            $category->displayOrder = $request->input('displayorder');
            $category->enabled      = $request->input('enabled');
            $category->isrecommand  = $request->input('isrecommand');
            !empty($thumb)          && $category->thumb = $thumb;
            !empty($parentid)       && $category->parentid = $parentid;
            $category->save();

            return redirect()->route('shop.category', $pagePram);
        }

        // 渲染
        $category = ShoppingCategory::where(['weid'=>$this->weid])
            ->orderBy('displayorder')->orderBy('id')
            ->paginate(10); //dd($category);

        $end    = microtime(true);
        return view('web.shop.category', [
            'category'  => $category,
            'pass'      => $end - $begin,
            'weid'      => $this->weid,
            'module'    => 'shopCategory'
        ]);
    }

    /**
     * 商品管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function goods(Request $request)
    {
        $begin = microtime(true);
        parse_str($request->getQueryString(), $pagePram); //dump($pagePram);
        $desPath    = 'imgs/uploads/images/'.date('Y').'/'.date('m').'/';


        // 添加和编辑
        $_token = $request->input('_token');
        if (isset($_token) && $_token == csrf_token()) {

            $id = $request->input('id');

            // 编辑回填模态框 [ajax]
            if ($request->input('op') == 'modalFill') {
                $goods = ShoppingGoods::with('category')->where(['id'=>$id])->first();

                $goods->timestart = date('Y-m-d H:i', $goods->timestart);
                $goods->timeend = date('Y-m-d H:i', $goods->timeend);
                $goods->timestart .= ' - ' . $goods->timeend;

                $goods->thumb_url = iunserializer($goods->thumb_url);
                //$cate = ShoppingGoods::where(['id'=>$request->input('id')])->first()->category;
                //$goods->cateName = $cate->name;

                return response()->json($goods);
            }

            // 删除记录
            if($request->input('op') == 'delete') {
                $goods = ShoppingGoods::where(['id'=>$id])->update(['deleted'=>1]);
                return response()->json($goods);
            }

            // 自动选择二级分类 [ajax]
            if ($request->input('op') == 'ccate') {
                //$id = $request->input('id');
                $category2 = ShoppingCategory::where(['weid'=>$this->weid, 'parentid'=>$id])->get();
                return response()->json($category2);
            }

            // 处理批量上传 [ajax]
            if($request->input('op') == 'fileinput'){

                $thumb = [];
                foreach ($_FILES['fileinput']['tmp_name'] as $key => $val) {
                    $fileFullName = $this->destPath.$this->fileName.'.jpg';
                    move_uploaded_file($val, $fileFullName);
                }
                return response()->json(['ajax'=>$request->ajax(), 'thumb_url'=>$fileFullName, 'FILES'=>$_FILES]);
            }

            // 删除批量中的单个
            if($request->input('op') == 'fileinput-del') {
                $goods = ShoppingGoods::where(['id'=>$id])->first();
                $thumb_url = $goods['thumb_url'];
                return response()->json(['ajax'=>$request->ajax(), 'thumb_url'=>$thumb_url]);
            }


                /*$this->validate($request, [
                    //'name'          => 'required|max:20',
                    //'description'   => 'required',
                    //'displayorder'  => 'required',
                    //'isrecommand'   => 'required'
                ]);*/


            $post = $request->input('goods'); //dd($post);
            $post['weid'] = $this->weid;
            $post['createtime'] = time();
            //$post['content'] = 'test';
            $post['spec'] = '';
            $post['isrecommand']    = empty($post['isrecommand']) ? 0 : 1;
            $post['isnew']          = empty($post['isnew']) ? 0 : 1;
            $post['ishot']          = empty($post['ishot']) ? 0 : 1;
            $post['istime']         = empty($post['istime']) ? 0 : 1;

            //dd($request->file());

            $request->file('goods.thumb') && $post['thumb'] = $this->uploadFile($request->file('goods.thumb'), $this->fileName, $this->destPath);
            /*$file = $request->file('goods.thumb');
            $realPath = $file->getRealPath();
            $ext = $file->getClientOriginalExtension();
            $filename = date('Y-m-d-H-i-S').'-'.uniqid().'-'.$ext;
            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));*/


            !empty($post['thumb_url'])  && $post['thumb_url'] = serialize($post['thumb_url']);

            if ($post['istime'] == 1) {
                $temp = explode(' - ', $post['timestart']);
                $post['timestart'] = strtotime($temp[0]);
                $post['timeend'] = strtotime($temp[1]);
            } else {
                unset($post['timestart']);
                unset($post['timeend']);
            }
            //dump(date('Y-m-d H:i:s', $post['timeend']));
            //dd($post);

            $goods = ShoppingGoods::updateOrCreate(['id'=>$post['id']], $post);

            //dd($pagePram);
            return redirect()->route('shop.goods', $pagePram);
        }


        $goods = ShoppingGoods::with('category')->where(['weid'=>$this->weid, 'deleted'=>0]);
        !empty($request->input('gid'))      && $goods = $goods->where(['id'=>$request->input('gid')]);
        !empty($request->input('status'))   && $goods = $goods->where(['status'=>$request->input('status')]);
        !empty($request->input('pcate'))    && $goods = $goods->where(['pcate'=>$request->input('pcate')]);
        !empty($request->input('ccate'))    && $goods = $goods->where(['ccate'=>$request->input('ccate')]);
        !empty($request->input('keyword'))  && $goods = $goods->where('title', 'like', '%'.$request->input('keyword').'%');
        $goods = $goods->paginate(10); //toSql();//

        $category1 = ShoppingCategory::where(['weid'=>$this->weid, 'parentid'=>0])->get();
        $category2 = ShoppingCategory::where(['weid'=>$this->weid, 'parentid'=>$request->input('pcate')])->get();
        //dd($goods);


        $end    = microtime(true);
        return view('web.shop.goods', [
            'goods'     => $goods,
            'category1'  => $category1,
            'category2'  => $category2,
            'pass'      => $end - $begin,
            'weid'      => $this->weid,
            'module'    => 'shopGoods',
            'pagePram'  => $pagePram,
            'stime'     => date('Y-m-d H:i:s', time()),
            'etime'     => date('Y-m-d H:i:s', strtotime("+1 month")),
        ]);
    }


    /**
     * 订单管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orders(Request $request)
    {
        $begin  = microtime(true);
        parse_str($request->getQueryString(), $pagePram); //dump($pagePram);

        // POST
        $_token = $request->input('_token');
        if (isset($_token) && $_token == csrf_token()) {

            $id = $request->input('id');
            $orders = ShoppingOrder::with('orderGoods', 'address', 'invoice')->where(['weid'=>$this->weid, 'id'=>$id])->first();

            // ajax
            if($request->ajax() && $request->input('op') == 'detail') {

                /*$orders = DB::table('shopping_order')
                    ->select('shopping_order_goods.*', 'shopping_goods.title', 'fans.realname', 'fans.mobile')
                    ->leftJoin('fans', 'shopping_order.from_user', '=', 'fans.from_user')
                    ->leftJoin('shopping_order_goods', 'shopping_order.id', '=', 'shopping_order_goods.orderid')
                    ->leftJoin('shopping_goods', 'shopping_order_goods.goodsid', '=', 'shopping_goods.id')
                    ->where(['shopping_order.weid'=>$this->weid, 'shopping_order.id'=>$id])->get();*/

                // 查看详情回填数据
                for($i=0; $i<count($orders['orderGoods']); $i++) {
                    $ogs = ShoppingGoods::select('id', 'title')->where(['id'=>$orders['orderGoods'][$i]['goodsid']])->first();
                    $orders['orderGoods'][$i] = array_merge(json_decode(json_encode($orders['orderGoods'][$i]), true), json_decode(json_encode($ogs), true));
                }
                $orders['createtime2'] = date('Y-m-d H:i:s', $orders['createtime']);
                return response()->json($orders);
            }

            // 验证输入
            $this->validate($request, [
                'remark'        => 'required|max:200'
            ]);

            // 判定更新
            switch($request->input('submit')) {
                case '确认发货':
                    // 验证输入
                    $this->validate($request, [
                        'expresscom'    => 'required',
                        'expresssn'     => 'required|max:16',
                    ]);

                    $orders->status     = 3;
                    $orders->expresscom = $request->input('expresscom');
                    $orders->expresssn  = $request->input('expresssn');
                    //ShoppingOrder::where(['weid'=>$this->weid, 'id'=>$id])->update(['status'=>3, 'updatetime'=>time(), 'remark'=>$remark, 'expresscom'=>$expresscom, 'expresssn'=>$expresssn]);
                    $pagePram['status'] = 3;
                    break;
                case '关闭订单':
                    $orders->status     = 5;
                    //ShoppingOrder::where(['weid'=>$this->weid, 'id'=>$id])->update(['status'=>5, 'updatetime'=>time(), 'remark'=>$remark]);
                    $pagePram['status'] = 5;
                    break;
                default:
            }
            $orders->updatetime = time();
            $orders->remark     = $request->input('remark');
            $orders->save();

            return redirect()->route('shop.orders', $pagePram);
        }

        // 筛选渲染
        $status = $request->input('status');
        $ordersn = $request->input('ordersn');
        $createtime = $request->input('createtime');

        $orders = ShoppingOrder::with('orderGoods', 'address', 'invoice')->where(['weid'=>$this->weid]);
        !empty($status)     && $orders = $orders->where(['status'=>$status]);
        !empty($ordersn)    && $orders = $orders->where(['ordersn'=>$ordersn]);

        if(!empty($createtime)){
            $createtime = explode(' - ', $createtime); // 2017-08-14 00:00 - 2017-09-15 23:59
            $beginTs    = strtotime($createtime[0].':00');//$createtime[0]; //
            $endTs      = strtotime($createtime[1].':59');//$createtime[1]; //
            //dump($createtime, $beginTs, $endTs);
            //(substr($cteatetime[0], 0, 10)<>substr($cteatetime[1], 0, 10)) &&
            $orders = $orders->where('createtime', '>', $beginTs)->where('createtime', '<', $endTs);
        }

        $orders = $orders
            ->orderBy('updatetime', 'desc')
            ->orderBy('createtime', 'desc')
            ->paginate(10);
        //dd($this->weid, $pagePram, $orders);

        $end    = microtime(true);
        return view('web.shop.order', [
            'pass'      => $end - $begin,
            'weid'      => $this->weid,
            'module'    => 'shopOrders',
            'orders'    => $orders,
            'pagePram'  => $pagePram,
            'status'    => $status
        ]);
    }

    /**
     * 商品幻灯片管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adv(Request $request)
    {
        $begin  = microtime(true);
        parse_str($request->getQueryString(), $pagePram); //dump($pagePram);

        // POST
        $_token = $request->input('_token');
        if (isset($_token) && $_token == csrf_token()) {

            $id     = $request->input('id');
            $adv    = ShoppingAdv::where(['weid'=>$this->weid, 'id'=>$id])->first();

            if ($request->ajax()) {

                // 编辑回填
                if($request->input('op') == 'edit'){
                    return response()->json($adv);
                }

                // 删除
                if($request->input('op') == 'del'){
                    $code = $adv->delete();
                    return response()->json(['code'=>$code]);
                }

            } //dd($request->input());

            // 验证输入
            $this->validate($request, [
                'advname'       => 'required',
                'link'          => 'required',
                'displayorder'  => 'required',
                'enabled'       => 'required',
            ]);

            // 添加
            if(empty($adv)){
                $adv = new ShoppingAdv();
            }

            // 上传图片
            $thumb = $request->file('thumb') ? $this->uploadFile($request->file('thumb'), $this->fileName, $this->destPath) : null;

            // 更新
            $adv->weid          = $this->weid;
            $adv->advname       = $request->input('advname');
            $adv->link          = $request->input('link');
            $adv->displayorder  = $request->input('displayorder');
            $adv->enabled       = $request->input('enabled');
            !empty($thumb)      && $adv->thumb = $thumb;
            $adv->save();

            return redirect()->route('shop.adv', $pagePram);
        }

        $advs = ShoppingAdv::where(['weid'=>$this->weid])
            ->orderBy('displayorder')->orderBy('id')
            ->paginate(10);; //dump($advs);

        $end    = microtime(true);
        return view('web.shop.adv', [
            'pass'      => $end - $begin,
            'weid'      => $this->weid,
            'module'    => 'shopAdv',
            'pagePram'  => $pagePram,
            'advs'      => $advs
        ]);
    }


    /**
     * 物流管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function express(Request $request)
    {
        $begin  = microtime(true);
        parse_str($request->getQueryString(), $pagePram); //dump($pagePram);


        if ($request->ajax()) {
            // 处理批量上传 [ajax]
            if ($request->input('op') == 'fileinput') {

                foreach ($_FILES['fileinput']['tmp_name'] as $key => $val) {
                    $fileFullName = $this->destPath . $this->fileName . '.jpg';
                    move_uploaded_file($val, $fileFullName);
                }
                return response()->json(['ajax' => $request->ajax(), 'thumb_url' => $fileFullName, 'FILES' => $_FILES]);
            }
        }


        // POST
        $_token = $request->input('_token');
        if (isset($_token) && $_token == csrf_token()) {

            $post = $request->input('goods');
            $post['thumb_url'] && $thumb_url = serialize($post['thumb_url']);
            ShoppingGoods::where(['id'=>222])->update(['thumb_url'=>$thumb_url]);

            return redirect()->route('shop.express', $pagePram);
        }

        $good = ShoppingGoods::where(['id'=>222])->first();
        $good['advs'] = iunserializer($good['thumb_url']);
        //dd($good);


        $end    = microtime(true);
        return view('web.shop.express', [
            'pass'      => $end - $begin,
            'weid'      => $this->weid,
            'module'    => 'shopExpress',
            'pagePram'  => $pagePram,
            'good'      => $good,
            'thumbJson' => json_encode($good['advs']),
        ]);
    }


    /**
     * @param $file
     * @param $name
     * @param $destPath
     * @return string
     */
    public function uploadFile($file, $name, $destPath){

        $data = [
            'fileName'      => $file->getClientOriginalName(),
            'fileExt'       => $file->getClientOriginalExtension(),
            'fileRealPath'  => $file->getRealPath(),
            'fileSize'      => $file->getSize(),
            'fileMimeType'  => $file->getMimeType(),

        ];
        //dd($data);
        //Move Uploaded File

        $fileName = $name.'.'.$data['fileExt'];//'.jpg';
        !file_exists($destPath) && mkdir($destPath,0755,true);
        file_exists($fileName)  && unlink($fileName);
        $file->move($destPath, $fileName); // $file->getClientOriginalName()
        //$file->store($destPath, $name.'.'.$file->getClientOriginalExtension());
        //dd($destPath);
        return $destPath.$fileName;
    }

    public function test()
    {
        //$url = 'http://p.beyondh.com/Home/NeedInputCaptcha?UserName=%E4%B9%9D%E5%A4%A9%E9%85%92%E5%BA%97&_=1502418097300';
        //$res = HttpRequest::origin($url);
        //dd($res);

        // 登陆请求
        $url = 'http://p.beyondh.com/Home/Login';
        $post = [
            'ID'            =>'九天酒店',//
            'Password'      =>'jt123456',
            'Shift'         => 0,
            'MacAddress'    =>'',
            'GeetestChallenge'  =>'',
            'GeetestValidate'   =>'',
            'GeetestSeccod'     =>'',
            'NewPassword'   =>'',
            'Sign'          =>'',
            'Timespan'      =>'',
        ];
        $cookie_jar = public_path('beyondh.cookie');
        $extra = array(
            'CURLOPT_COOKIEJAR'     => $cookie_jar,
            'CURLOPT_COOKIEFILE'    => $cookie_jar
        );

        $res = HttpRequest::content($url, $post, $extra); //content //origin
        $key = random(20);
        //echo "<script>window.localStorage.setItem('{$key}', '{$res}');</script>";
        //dd($url, $cookie_jar, $res);

        // 在线培训
        $url = 'http://tutorial.beyondh.com/inspire/app/index.html?r=p.beyondh.com';
        $res = HttpRequest::origin($url, null, $extra);
        dd($url, $res);


        // 收集地址
        $url = 'http://tutorial.beyondh.com/inspire/app/help.html';
        $res = HttpRequest::content($url, null, $extra);

        $rule = '#<td>>_.*?<a href="(.*?)" target="_blank">#i';
        if (preg_match_all($rule, $res, $matches)){

            //dd($matches);

            $urls = [];
            foreach ($matches[1] as $path) {
                $url1 = 'http://tutorial.beyondh.com/inspire/app/'.$path;
                $urls[] = $url1;

                // 下层请求
                $res1 = HttpRequest::content($url1, null, $extra);
                $ruleTitle = '##i';
                dd($url1, $res1);
            }

            dd($urls);
        }

        return view('test', [
            'judge' => 'jt',
            'urls'  => $urls,
            'age'   => 1
        ]);
    }
}
