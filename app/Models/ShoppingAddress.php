<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingAddress extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_address';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
