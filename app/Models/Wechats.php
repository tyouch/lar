<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wechats extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'wechats';
    protected $primaryKey   = 'weid';
    protected $keyType      = 'int';
    //public $incrementing    = false;
    public $timestamps      = false;
}
