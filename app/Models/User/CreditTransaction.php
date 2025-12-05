<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'change_type',
        'credits',
        'reference_type',
        'reference_id',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

