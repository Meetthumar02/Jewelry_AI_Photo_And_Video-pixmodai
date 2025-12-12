<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelDesign extends Model
{
    use HasFactory;

    protected $table = 'model_designs';

    protected $fillable = ['name', 'image', 'category', 'description', 'is_active', 'sort_order', 'industry_id', 'category_id', 'product_type_id', 'shoot_type_id'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function shootType()
    {
        return $this->belongsTo(ShootType::class, 'shoot_type_id');
    }
}
