<?php

namespace App;

use App\Helper\StringHelper;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'muser';

    public $fillable = [
        'm_id',
        'token',
        'o_id'
    ];

    protected $hidden = [
        'password'
    ];

    public static function generateToken()
    {
        return StringHelper::generateRandomString(20);
    }
}
