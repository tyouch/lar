<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'account';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    //public $incrementing    = false;
    public $timestamps      = false;
}
