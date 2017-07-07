<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'ip';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    //public $incrementing    = false;
    public $timestamps      = false;
}
