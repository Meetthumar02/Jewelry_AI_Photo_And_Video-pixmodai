<?php

namespace App\Http\Controllers\User;

use App\Models\User\AIPhotoShoot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogStudioController extends Controller
{
    /**
     * Display the AI Photo Shoot page
     * Route: GET /ai-photoshoot
     */
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $isSubscribed = strtolower($user->is_subscribed ?? 'false') === 'true';

        // Get user's previous shoots
        $previousShoots = AIPhotoShoot::forUser($userId)
            ->latest()
            ->take(10)
            ->get();

        // Model design templates
        $modelDesigns = $this->getModelDesigns();

        return view('user.ai_photoshoot', compact(
            'isSubscribed',
            'previousShoots',
            'modelDesigns'
        ));
    }

    /**
     * Get model design templates
     * Returns array of available model designs
     */
    private function getModelDesigns()
    {
        return [
            [
                'id' => 'model_1',
                'name' => 'Classic Model 1',
                'thumbnail' => '/images/models/model_1.jpg',
                'category' => 'classic',
                'description' => 'Professional studio setup with neutral background'
            ],
            [
                'id' => 'model_2',
                'name' => 'Classic Model 2',
                'thumbnail' => '/images/models/model_2.jpg',
                'category' => 'classic',
                'description' => 'Elegant pose with soft lighting'
            ],
            [
                'id' => 'model_3',
                'name' => 'Lifestyle Model 1',
                'thumbnail' => '/images/models/model_3.jpg',
                'category' => 'lifestyle',
                'description' => 'Natural setting with casual vibe'
            ],
            [
                'id' => 'model_4',
                'name' => 'Luxury Model 1',
                'thumbnail' => '/images/models/model_4.jpg',
                'category' => 'luxury',
                'description' => 'High-end premium look with dramatic lighting'
            ],
            [
                'id' => 'model_5',
                'name' => 'Outdoor Model 1',
                'thumbnail' => '/images/models/model_5.jpg',
                'category' => 'outdoor',
                'description' => 'Natural outdoor environment'
            ],
            [
                'id' => 'model_6',
                'name' => 'Outdoor Model 2',
                'thumbnail' => '/images/models/model_6.jpg',
                'category' => 'outdoor',
                'description' => 'Bright natural daylight setting'
            ],
        ];
    }

    /**
     * Upload and process image
     * Route: POST /ai-photoshoot/upload
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('image');

            // Generate unique filename
            $filename = 'photoshoot_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Store in public disk
            $path = $file->storeAs('photoshoots/uploads', $filename, 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path),
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Start photo shoot generation
     * Route: POST /ai-photoshoot/start
     */
    public function startShoot(Request $request)
    {
        $request->validate([
            'industry' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'product_type' => 'required|string|max:100',
            'shoot_type' => 'required|string|max:50',
            'model_design_id' => 'required|string|max:50',
            'uploaded_image' => 'required|string',
            'aspect_ratio' => 'required|string|max:10',
            'output_format' => 'required|string|in:JPEG,PNG',
        ]);

        $userId = Auth::id();
        $user = Auth::user();

        // Check credits (1 shoot = 20 credits)
        $creditsNeeded = 20;

        if ($user->total_credits < $creditsNeeded) {
            return response()->json([
                'success' => false,
                'message' => "Not enough credits! Required: {$creditsNeeded}, Available: {$user->total_credits}",
            ], 400);
        }

        try {
            // Create shoot record with processing status
            $shoot = AIPhotoShoot::create([
                'user_id' => $userId,
                'industry' => $request->industry,
                'category' => $request->category,
                'product_type' => $request->product_type,
                'shoot_type' => $request->shoot_type,
                'model_design_id' => $request->model_design_id,
                'uploaded_image' => $request->uploaded_image,
                'aspect_ratio' => $request->aspect_ratio,
                'output_format' => $request->output_format,
                'status' => 'processing',
                'credits_used' => $creditsNeeded,
            ]);

            // Deduct credits from user
            $user->total_credits -= $creditsNeeded;
            $user->save();

            // Log credit transaction
            \App\Models\User\CreditTransaction::create([
                'user_id' => $userId,
                'change_type' => 'use',
                'credits' => -$creditsNeeded,
                'reference_type' => 'ai_photoshoot',
                'reference_id' => $shoot->id,
                'note' => "AI Photo Shoot - {$request->product_type} ({$request->shoot_type})",
            ]);

            // Generate the actual photo (AI processing)
            $this->generatePhotos($shoot);

            // Reload shoot to get updated data
            $shoot->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Photo shoot completed successfully!',
                'shoot_id' => $shoot->id,
                'shoot' => $shoot,
            ]);

        } catch (\Exception $e) {
            // Rollback credits if error occurs
            if (isset($shoot) && $shoot->exists) {
                $user->total_credits += $creditsNeeded;
                $user->save();
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to start photo shoot: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate photos using AI service
     * This is where you integrate with your actual AI API
     */
    private function generatePhotos($shoot)
    {
        try {
            // IMPORTANT: Replace this with your actual AI API integration
            // Example services: Stability AI, Midjourney API, Replicate, etc.

            // For demonstration, we'll simulate the process
            // In production, you would:
            // 1. Send image + parameters to AI service
            // 2. Wait for processing
            // 3. Receive generated image URL
            // 4. Download and store the image

            // Simulate processing time
            sleep(2);

            // Build the AI prompt based on shoot parameters
            $prompt = $this->buildAIPrompt($shoot);

            // Mock generated image path (replace with actual AI service response)
            $generatedImagePath = $this->mockGenerateImage($shoot);

            // Update shoot with results
            $shoot->update([
                'generated_images' => [$generatedImagePath],
                'prompts_used' => [$prompt],
                'status' => 'completed',
            ]);

            return true;

        } catch (\Exception $e) {
            // Update shoot status to failed
            $shoot->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Build AI prompt from shoot parameters
     */
    private function buildAIPrompt($shoot)
    {
        $prompts = [
            'Classic' => "Professional jewelry product photography, {$shoot->product_type} on model, studio lighting, clean background, high quality, commercial photo, 4K, sharp focus",
            'Lifestyle' => "Lifestyle jewelry photography, {$shoot->product_type} worn naturally, candid moment, soft natural lighting, elegant composition, professional quality",
            'Luxury' => "Luxury high-end jewelry photography, {$shoot->product_type} on model, dramatic lighting, premium look, editorial style, glamorous, sophisticated, ultra detailed",
            'Outdoor' => "Outdoor jewelry photography, {$shoot->product_type} on model, natural daylight, scenic background, fresh and vibrant, professional quality, beautiful lighting",
        ];

        $basePrompt = $prompts[$shoot->shoot_type] ?? $prompts['Classic'];

        // Add technical specifications
        $prompt = $basePrompt . ", aspect ratio {$shoot->aspect_ratio}, {$shoot->output_format} format, professional color grading";

        return $prompt;
    }

    /**
     * Mock image generation (replace with real AI service)
     */
    private function mockGenerateImage($shoot)
    {
        // In production, this would call your AI service API
        // For now, we'll just copy the uploaded image as a placeholder

        $uploadPath = str_replace('/storage/', '', $shoot->uploaded_image);

        if (Storage::disk('public')->exists($uploadPath)) {
            // Generate output filename
            $outputFilename = 'generated_' . time() . '_' . Str::random(10) . '.jpg';
            $outputPath = 'photoshoots/generated/' . $outputFilename;

            // Copy uploaded image to generated folder (in production, this would be the AI output)
            Storage::disk('public')->copy($uploadPath, $outputPath);

            return Storage::url($outputPath);
        }

        return null;
    }

    /**
     * Get shoot status
     * Route: GET /ai-photoshoot/status/{id}
     */
    public function getStatus($id)
    {
        $shoot = AIPhotoShoot::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'shoot' => $shoot,
            'is_completed' => $shoot->isCompleted(),
            'is_processing' => $shoot->isProcessing(),
        ]);
    }

    /**
     * Download generated image
     * Route: GET /ai-photoshoot/download/{id}
     */
    public function downloadImage($id)
    {
        $shoot = AIPhotoShoot::where('user_id', Auth::id())
            ->findOrFail($id);

        if (!$shoot->isCompleted()) {
            abort(400, 'Photo shoot not completed yet');
        }

        $firstImage = $shoot->first_image;

        if (!$firstImage) {
            abort(404, 'No generated image found');
        }

        // Remove /storage/ prefix to get actual file path
        $filePath = str_replace('/storage/', '', $firstImage);

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Image file not found on server');
        }

        // Get original filename for download
        $filename = "ai_photoshoot_{$shoot->product_type}_{$id}.jpg";

        return Storage::disk('public')->download($filePath, $filename);
    }

    /**
     * Delete shoot and associated images
     * Route: DELETE /ai-photoshoot/{id}
     */
    public function destroy($id)
    {
        $shoot = AIPhotoShoot::where('user_id', Auth::id())
            ->findOrFail($id);

        try {
            // Delete uploaded image
            if ($shoot->uploaded_image) {
                $uploadPath = str_replace('/storage/', '', $shoot->uploaded_image);
                if (Storage::disk('public')->exists($uploadPath)) {
                    Storage::disk('public')->delete($uploadPath);
                }
            }

            // Delete generated images
            if ($shoot->generated_images && is_array($shoot->generated_images)) {
                foreach ($shoot->generated_images as $image) {
                    $imagePath = str_replace('/storage/', '', $image);
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            // Delete shoot record
            $shoot->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo shoot deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shoot: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's shoot history
     * Route: GET /ai-photoshoot/history
     */
    public function history()
    {
        $shoots = AIPhotoShoot::forUser(Auth::id())
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'shoots' => $shoots,
        ]);
    }
}
