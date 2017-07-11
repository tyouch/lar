<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/7/10
 * Time: 16:46
 */

return [
    'AppID'         => 'wxaa8613a62ef8e9e4',
    'AppSecret'     => '01032e6069013e3f2171e48dbfd559df',
    'redirectUri'   => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'] . '/wetest/', //lar/public/
    'wPayUrl'       => 'https://api.mch.weixin.qq.com/pay/unifiedorder',
    'mchID'         => '1484736102',
    'apiKey'        => 'I1sifX1iA973c6N19rXXIOvvFv9HP7Fw'
];