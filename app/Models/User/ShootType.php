<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShootType extends Model
{
    use HasFactory;

    protected $table = 'shoot_types';

    protected $fillable = [
        'name',
        'value',
        'image',
        'status',
    ];
}
