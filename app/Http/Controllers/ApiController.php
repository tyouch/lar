<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Wechats;
use App\Models\Rule;
use App\Models\RuleKeyword;

class ApiController extends Controller
{
    private $redirectUri;

    public function __construct()
    {
        $this->redirectUri = config('wechat.redirectUri');
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
            $ruleK      = RuleKeyword::where(['weid' => $acctount['weid'], 'content'=>$recMsg['Content']])
                //->where('content', 'like', '%你好%')
                ->first();
            $ruleR = Rule::where(['id' => $ruleK['rid']])->where('status', '>', 0)->first();

            if($recMsg['Content'] == $ruleK['content']){

                switch ($ruleK['module']) { //$recMsg['MsgType']
                    case 'basic':
                        $ruleR = $ruleR->basicReply; // Rule::where(['id' => $ruleK['rid']])->first()
                        //foreach ($ruleR as $reply) {
                            $sendMsgs = [ //[]
                                'ToUserName'    => $recMsg['FromUserName'],
                                'FromUserName'  => $recMsg['ToUserName'],
                                'CreateTime'    => time(),
                                'MsgType'       => 'text',
                                'Content'       => $ruleR[0]['content'],//$reply
                            ];
                        //}
                        break;
                    case 'news':
                        $ruleR = $ruleR->newsReply;
                        $sendMsgs = [ //[]
                            'ToUserName'    => $recMsg['FromUserName'],
                            'FromUserName'  => $recMsg['ToUserName'],
                            'CreateTime'    => time(),
                            'MsgType'       => 'news', //'Content' => $ruleR[0]['content'],
                            'ArticleCount'  => 1,
                            'Articles'      => [
                                'item'  =>[
                                    'Title'         => $ruleR[0]['title'],
                                    'Description'   => $ruleR[0]['description'],
                                    'PicUrl'        => $this->redirectUri.$ruleR[0]['thumb'],
                                    'Url'           => $ruleR[0]['url']
                                ]
                            ]
                        ];
                        break;
                }
                $xmlReply = array2xml($sendMsgs);//[2]
                Log::info('回复的内容：' . PHP_EOL . xmlFormatting($xmlReply) . PHP_EOL);//[2]
                echo $xmlReply;
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
