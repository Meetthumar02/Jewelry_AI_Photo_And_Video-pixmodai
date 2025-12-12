<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'industry_id',
        'name',
        'image',
        'status',
    ];

    /**
     * Relationship: Category belongs to Industry
     */
    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }
}
