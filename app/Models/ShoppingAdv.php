<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingAdv extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_adv';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
