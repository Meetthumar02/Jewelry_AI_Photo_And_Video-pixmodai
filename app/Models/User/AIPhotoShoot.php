<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AIPhotoShoot extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'ai_photoshoot';
    protected $table = 'ai_photo_shoots';

    protected $fillable = [
        'user_id',
        'industry',
        'category',
        'product_type',
        'shoot_type',
        'model_design_id',
        'uploaded_image',
        'aspect_ratio',
        'output_format',
        'generated_images',
        'prompts_used',
        'credits_used',
        'status',
        'error_message',
    ];

    protected $casts = [
        'generated_images' => 'array',
        'prompts_used' => 'array',
        'credits_used' => 'integer',
    ];

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the first generated image
     */
    public function getFirstImageAttribute()
    {
        $images = $this->generated_images;
        return is_array($images) && count($images) > 0 ? $images[0] : null;
    }

    /**
     * Check if shoot is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if shoot is processing
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Scope: Get user's shoots
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Completed shoots
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
