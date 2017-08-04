<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingGoods extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_goods';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Models\ShoppingCategory','ccate','id');
    }
}

