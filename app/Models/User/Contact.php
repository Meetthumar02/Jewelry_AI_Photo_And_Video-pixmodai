<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'how_can_we_help',
        'message'
    ];
}
