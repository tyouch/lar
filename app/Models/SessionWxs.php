<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionWxs extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'session_wxs';
    protected $primaryKey   = 'session_3rd_key';
    protected $keyType      = 'string';
    public $incrementing    = false;
    public $timestamps      = false;

    protected $guarded = [];
}
