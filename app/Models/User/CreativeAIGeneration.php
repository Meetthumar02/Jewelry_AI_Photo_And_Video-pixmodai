<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CreativeAIGeneration extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'creative_ai';
    protected $table = 'creative_ai_generations';

    protected $fillable = [
        'user_id',
        'prompt',
        'uploaded_image',
        'aspect_ratio',
        'output_format',
        'generated_images',
        'ai_response',
        'credits_used',
        'status',
        'error_message',
    ];

    protected $casts = [
        'generated_images' => 'array',
        'credits_used' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFirstImageAttribute()
    {
        $images = $this->generated_images;

        if (is_array($images) && isset($images[0])) {
            return $images[0];
        }

        return null;
    }

    public function getAllImagesAttribute()
    {
        return is_array($this->generated_images) ? $this->generated_images : [];
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'completed' => 'success',
            'processing' => 'info',
            'failed' => 'danger',
            'draft' => 'secondary',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'completed' => 'Completed',
            'processing' => 'Processing',
            'failed' => 'Failed',
            'draft' => 'Draft',
            default => 'Unknown',
        };
    }

    public function getShortPromptAttribute()
    {
        return strlen($this->prompt) > 100
            ? substr($this->prompt, 0, 100) . '...'
            : $this->prompt;
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function markAsProcessing()
    {
        return $this->update(['status' => 'processing']);
    }

    public function markAsCompleted(array $images = [], $aiResponse = [])
    {
        return $this->update([
            'status' => 'completed',
            'generated_images' => $images,
            'ai_response' => is_array($aiResponse) ? json_encode($aiResponse) : null,
        ]);
    }

    public function markAsFailed($errorMessage)
    {
        return $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($generation) {

            if ($generation->uploaded_image) {
                $path = str_replace('/storage/', '', $generation->uploaded_image);
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }

            if (is_array($generation->generated_images)) {
                foreach ($generation->generated_images as $image) {
                    $path = str_replace('/storage/', '', $image);
                    if (file_exists(public_path($path))) {
                        unlink(public_path($path));
                    }
                }
            }
        });
    }
}
