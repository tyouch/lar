<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsReply extends Model
{
    protected $connection   = 'mysql';
    protected $table        = 'news_reply';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    public function rule()
    {
        return $this->belongsTo('App\Models\Rule','rid','id');
    }
}
