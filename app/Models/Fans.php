<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fans extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'fans';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    //public $incrementing    = false;
    public $timestamps      = false;
}
