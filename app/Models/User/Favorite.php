<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catalog_studio_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function catalogStudio()
    {
        return $this->belongsTo(CatalogStudio::class);
    }
}

