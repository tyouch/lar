<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\ShoppingCategory;
use App\Models\ShoppingGoods;


class ShopController extends Controller
{
    private $weid;

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->weid = $request->input('weid');
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
        $_token = $request->input('_token');
        //dd($_token, csrf_token());
        if (isset($_token) && $_token == csrf_token()) {

            $this->validate($request, [
                'name'          => 'required|max:20',
                'description'   => 'required',
                'displayorder'  => 'required',
                'enabled'        => 'required',
                'isrecommand'   => 'required'
            ]);

            $id = $request->input('id');

            if (empty($id)) {
                $category = new ShoppingCategory();
            } else {
                $category = ShoppingCategory::where(['weid' => $this->weid, 'id' => $id])->first();
            }

            $thumb = $request->file('thumb') ?
                $this->uploadFile($request->file('thumb'), random(10), 'imgs/uploads/images/'.date('Y').'/'.date('m').'/') : null;
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

            return redirect()->route('shop.category', ['weid'=>$this->weid]);
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


        // 添加和编辑
        $_token = $request->input('_token');
        if (isset($_token) && $_token == csrf_token()) {

            // 编辑回填模态框 [ajax]
            if ($request->input('op') == 'modalFill') {
                $goods = ShoppingGoods::where(['id' => $request->input('id')])->first();
                $goods->timestart = date('Y-m-d H:i', $goods->timestart);
                $goods->timeend = date('Y-m-d H:i', $goods->timeend);
                $goods->timestart .= ' - ' . $goods->timeend;
                return response()->json($goods);
            }

            // 自动选择二级分类 [ajax]
            if ($request->input('op') == 'ccate') {
                $id = $request->input('id');
                $category2 = ShoppingCategory::where(['weid'=>$this->weid, 'parentid'=>$id])->get();
                return response()->json($category2);
            }

            // 处理批量上传 [ajax]
            if($request->input('op') == 'fileinput'){
                $file       = $request->file('thumb_url');
                $desPath    = 'imgs/uploads/images/'.date('Y').'/'.date('m').'/';
                $thumb      = $file ? $this->uploadFile($file, random(10), $desPath) : null;

                return response()->json(['a'=>$request->file(),'b'=>$thumb]);
            }


            /*$this->validate($request, [
                //'name'          => 'required|max:20',
                //'description'   => 'required',
                //'displayorder'  => 'required',
                //'isrecommand'   => 'required'
            ]);*/


            $post = $request->input('goods');
            //dd($post['timestart'],$post['istime']);
            $post['weid'] = $this->weid;
            $post['createtime'] = time();
            $post['content'] = 'test';
            $post['spec'] = '';
            $post['isrecommand']    = empty($post['isrecommand']) ? 0 : 1;
            $post['isnew']          = empty($post['isnew']) ? 0 : 1;
            $post['ishot']          = empty($post['ishot']) ? 0 : 1;
            $post['istime']         = empty($post['istime']) ? 0 : 1;

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
        }


        $goods = ShoppingGoods::with('category')->where(['weid'=>$this->weid]);
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





    public function order()
    {
        dd('shop.order');
    }

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

}
