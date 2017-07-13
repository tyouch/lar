<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paylog extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'paylog';
    protected $primaryKey   = 'plid';
    protected $keyType      = 'int';
    //public $incrementing    = false;
    public $timestamps      = false;
}

