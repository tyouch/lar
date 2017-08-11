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
    }

    public function fans()
    {
        return $this->belongsTo('App\Models\Fans', 'from_user', 'from_user');
    }
}
