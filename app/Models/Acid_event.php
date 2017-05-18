<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acid_event extends Model
{
    protected $connection   = 'alienvault_siem';
    protected $table        = 'acid_event';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
    public $incrementing    = false;
    public $timestamps      = false;

    public function pluginSid()
    {
        return $this->belongsTo('App\Models\Plugin_sid', 'plugin_sid', 'sid');
    }
}
