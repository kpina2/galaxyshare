<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'message';
    protected $connection= 'mysql2';
    
    protected $fillable = [
        'message', 'required', 'active', 'sku', 'sort_order'
    ];
}