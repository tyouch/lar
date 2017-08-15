<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingInvoice extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'shopping_invoice';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $guarded = [];
}
