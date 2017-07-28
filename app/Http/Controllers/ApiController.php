<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Wechats;
use App\Models\Rule;
use App\Models\RuleKeyword;

class ApiController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $echostr = $request->input('echostr');
        if(!empty($echostr)) {
            $hash       = $request->input('hash');
            $signature  = $request->input('signature');
            $timestamp  = $request->input('timestamp');
            $nonce      = $request->input('nonce');

            $acctount   = Wechats::where(['hash'=>$hash])->first();
            $token      = $acctount['token'];

            if($this->checkSign($token, $timestamp, $nonce, $signature)) {
                return $echostr;
            }
        } else {
            $xml = file_get_contents('php://input', 'r');
            if (empty($xml)) {
                Log::info('无接收内容：' . PHP_EOL . $xml . PHP_EOL);
                exit;
            }
            Log::info('接收的内容：' . PHP_EOL . $xml . PHP_EOL);
            $recMsg     = xmlToArray($xml);
            $acctount   = Wechats::where(['original'=>$recMsg['ToUserName']])->first();

            if ($recMsg['MsgType'] == 'text') {

                $ruleK = RuleKeyword::where(['weid' => $acctount['weid']])
                    ->where('content', 'like', '%你好%')
                    ->first();
                $ruleR = Rule::where(['id' => $ruleK['rid']])->first()->reply;

                switch($recMsg['Content']){
                    case $ruleK['content']:
                        foreach ($ruleR as $reply) {
                            $sendMsgs[] = [
                                'ToUserName'    => $recMsg['FromUserName'],
                                'FromUserName'  => $recMsg['ToUserName'],
                                'CreateTime'    => time(),
                                'MsgType'       => 'text',
                                'Content'       => $reply['content'],
                            ];
                        }
                        //$msg = $ruleR[0]['content']; break;
                    default:
                }


                //foreach ($sendMsgs as $sendMsg) {
                    Log::info('接收的内容：' . PHP_EOL . json_encode($sendMsgs[2], JSON_UNESCAPED_UNICODE) . PHP_EOL);
                    echo array2xml($sendMsgs[2]);
                //}

            }

        }
    }

    public static function checkSign($token, $timestamp, $nonce, $signature)
    {
        $signkey = array($token, $timestamp, $nonce);
        sort($signkey, SORT_STRING);
        $signString = implode($signkey);
        $signString = sha1($signString);
        if ($signString == $signature) {
            Log::info('验签成功：' . $signString . PHP_EOL);
            return true;
        } else {
            Log::info('验签失败：' . $signString . PHP_EOL);
            return false;
        }
    }
}
