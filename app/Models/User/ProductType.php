<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_types';

    protected $fillable = [
        'category_id',
        'name',
        'image',
        'status',
    ];

    /**
     * Relationship: ProductType belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
