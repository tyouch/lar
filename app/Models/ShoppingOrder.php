<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingOrder extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_order';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function orderGoods()
    {
        return $this->hasMany('App\Models\ShoppingOrderGoods', 'orderid', 'id');
        //return $this->morphMany('App\Models\ShoppingOrderGoods', 'ogs');
    }

    public function address()
    {
        return $this->hasOne('App\Models\ShoppingAddress', 'id', 'addressid');
    }

    public function invoice()
    {
        return $this->hasOne('App\Models\ShoppingInvoice', 'id', 'invoice');
    }

    public function goods()
    {
        //return $this->hasManyThrough('App\Models\ShoppingGoods', 'App\Models\ShoppingOrderGoods', 'orderid', 'id', 'id');
    }

}
