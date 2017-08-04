<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCategory extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_category';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    public function goods()
    {
        return $this->hasMany('App\Models\ShoppingGoods', 'ccate', 'id');
    }
}
