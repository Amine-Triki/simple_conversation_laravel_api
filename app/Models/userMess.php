<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class userMess extends Authenticatable

{
    use HasFactory, Notifiable , HasApiTokens;

    protected $table = 'user_mess';

    protected $fillable = [
        'login',
        'email',
        'password',
        'telephone',
        'adress',
        'date_naissance',

    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
