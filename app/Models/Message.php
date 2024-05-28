<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    protected $fillable = [
        'id_user_sender',
        'id_user_receiver',
        'text_msg',
        'date_envoie',
        'etat',
    ];
}
