<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatalogStudio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'gender',
        'metal_type',
        'jewelry_type',
        'model_type',
        'mode_type',
        'body_type',
        'skin_tone',
        'hair_length',
        'hair_style',
        'background_location',
        'background',
        'design_desc',
        'reference_image',
        'photo_count',
        'prompts_json',
    ];

    protected $casts = [
        'prompts_json' => 'array',
    ];
    public function photos()
{
    return $this->hasMany(AIPhotoShoot::class, 'catalog_id');
}

}
