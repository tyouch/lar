<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin_sid extends Model
{
    protected $connection   = 'alienvault';
    protected $table        = 'plugin_sid';
    protected $primaryKey   = 'sid';
    protected $keyType      = 'int';
    public $incrementing    = false;
    public $timestamps      = false;

    public function acidEnent()
    {
        return $this->hasMany('App\Models\Acid_event', 'plugin_sid', 'sid');
    }
}
