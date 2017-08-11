<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingOrderGoods extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_order_goods';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo('App\Models\ShoppingOrder','orderid','id');
    }
}
