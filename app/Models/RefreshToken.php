<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $fillable = [
        'id',
        'token',
        'user_id',
        'expires_at',
        'revoked_at',
    ];

//    protected $casts = [
//        'expires_at' => 'datetime',
//        'revoked_at' => 'datetime',
//    ];

    public $incrementing = false;
    protected $keyType = 'string';
}
