<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'rule';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    public function keyword()
    {
        return $this->hasMany('App\Models\RuleKeyword', 'rid', 'id');
    }

    public function basicReply()
    {
        return $this->hasMany('App\Models\BasicReply', 'rid', 'id');
    }

    public function newsReply()
    {
        return $this->hasMany('App\Models\NewsReply', 'rid', 'id');
    }
}