<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShoppingAdv;
use App\Models\ShoppingGoods;

class ShopController extends Controller
{
    private $weid;

    public function __construct(Request $request)
    {
        $this->weid = $request->input('weid');
        //$this->middleware('auth:api');
    }

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

    public function getIndexGoods(Request $request)
    {
        $goods = ShoppingGoods::where(['weid'=>$this->weid, 'isrecommand'=>1])->get(); //dd($goods);
        return response()->json($goods);
    }
}
