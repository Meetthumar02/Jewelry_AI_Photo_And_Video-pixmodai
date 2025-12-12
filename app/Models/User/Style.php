<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $table = 'styles';

    protected $fillable = [
        'industry_id',
        'category_id',
        'product_type_id',
    ];

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
}
