<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/5/2
 * Time: 10:11
 */

namespace App\Libraries\Encryption;

use App\Libraries\Encryption\MyBase;

class MySub1 extends MyBase
{
    public function __construct($sign, $ciphertext)
    {

        parent::__construct($sign, $ciphertext);
    }

    public function getMsg()
    {
        //return parent::getMsg(); // TODO: Change the autogenerated stub
        return 'sub:'.$this->message;
    }
}