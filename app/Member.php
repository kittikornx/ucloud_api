<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'muser';

    public $fillable = [
        'm_id',
        'user',
        'password',
        'token',
        'o_id',
    ];
    protected $hidden = [
        'password'
    ];
    
}
