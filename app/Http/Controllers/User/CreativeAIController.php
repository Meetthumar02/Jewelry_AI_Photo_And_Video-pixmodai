<?php
// app/Http/Controllers/User/CreativeAIController.php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User\CreativeAIGeneration;
use App\Models\User\CreditTransaction;
use Illuminate\Validation\ValidationException;

class CreativeAIController extends Controller
{
    const CREDITS_PER_GENERATION = 20;
    const MAX_FILE_SIZE = 10485760;
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];

    public function index()
    {
        $modelDesigns = [['id' => 'classic_model_1', 'name' => 'Classic Model 1', 'thumbnail' => 'https://picsum.photos/id/237/200/300', 'category' => 'classic'], ['id' => 'classic_model_2', 'name' => 'Classic Model 2', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'classic'], ['id' => 'lifestyle_model_1', 'name' => 'Lifestyle Model 1', 'thumbnail' => 'https://picsum.photos/200/300?grayscale', 'category' => 'lifestyle'], ['id' => 'luxury_model_1', 'name' => 'Luxury Model 1', 'thumbnail' => 'https://picsum.photos/id/870/200/300?grayscale&blur=2', 'category' => 'luxury'], ['id' => 'outdoor_model_1', 'name' => 'Outdoor Model 1', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'outdoor']];

        return view('user.ai_studio', compact('modelDesigns'));
    }

    public function uploadImage(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json(['success' => false, 'message' => 'No file received'], 422);
            }

            $file = $request->file('image');
            if ($file->getSize() > self::MAX_FILE_SIZE) {
                return response()->json(['success' => false, 'message' => 'File too large (max 10MB)'], 422);
            }
            if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
                return response()->json(['success' => false, 'message' => 'Invalid file type'], 422);
            }

            $filename = 'creative_upload_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload');

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $file->move($destination, $filename);
            $path = 'upload/' . $filename;

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset($path),
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error('UPLOAD CRASH: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Upload failed'], 500);
        }
    }

    public function generate(Request $request)
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|min:10|max:2000',
                'uploaded_image' => 'nullable|string',
                'aspect_ratio' => 'required|string|in:1:1,4:3,16:9,3:4,9:16',
                'output_format' => 'required|string|in:JPEG,PNG',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
            }

            $creditsNeeded = self::CREDITS_PER_GENERATION;
            if ($user->total_credits < $creditsNeeded) {
                return response()->json(['success' => false, 'message' => 'Insufficient credits'], 400);
            }

            $generation = CreativeAIGeneration::create([
                'user_id' => $user->id,
                'prompt' => $validated['prompt'],
                'uploaded_image' => $validated['uploaded_image'] ?? null,
                'aspect_ratio' => $validated['aspect_ratio'],
                'output_format' => $validated['output_format'],
                'status' => 'processing',
                'credits_used' => $creditsNeeded,
            ]);

            $user->total_credits -= $creditsNeeded;
            $user->save();

            CreditTransaction::create([
                'user_id' => $user->id,
                'change_type' => 'use',
                'credits' => -$creditsNeeded,
                'reference_type' => 'creative_ai',
                'reference_id' => $generation->id,
                'note' => 'Creative AI generation started',
            ]);

            Log::info('Creative AI Generation queued', ['generation_id' => $generation->id, 'user_id' => $user->id]);

            $enhancedPrompt = $this->enhancePrompt($validated['prompt'], $validated['aspect_ratio']);
            $generatedImagePath = $this->mockGenerateCreativeImage($generation, $enhancedPrompt);

            if (!$generatedImagePath) {
                $user->total_credits += $creditsNeeded;
                $user->save();
                CreditTransaction::create([
                    'user_id' => $user->id,
                    'change_type' => 'refund',
                    'credits' => $creditsNeeded,
                    'reference_type' => 'creative_ai',
                    'reference_id' => $generation->id,
                    'note' => 'Refund - generation failed',
                ]);
                $generation->status = 'failed';
                $generation->save();

                return response()->json(['success' => false, 'message' => 'Image generation failed. Credits refunded.'], 500);
            }

            $generation->status = 'completed';
            $generation->generated_images = [$generatedImagePath];
            $generation->service_response = [
                'prompt' => $validated['prompt'],
                'enhanced_prompt' => $enhancedPrompt,
                'service' => 'mock',
                'time' => now()->toDateTimeString(),
            ];
            $generation->save();

            $generation->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Image generated successfully!',
                'generation_id' => $generation->id,
                'generation' => $generation,
                'credits_remaining' => $user->total_credits,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Creative AI generation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Server error creating generation'], 500);
        }
    }

    private function enhancePrompt($userPrompt, $aspectRatio)
    {
        $qualityKeywords = ['ultra detailed', 'high quality', 'professional photography', '8k resolution', 'realistic', 'sharp focus', 'vibrant colors'];
        $aspectEnhancements = [
            '1:1' => 'square composition, centered subject, balanced frame',
            '4:3' => 'standard photography composition, classic aspect',
            '16:9' => 'cinematic wide angle composition, landscape orientation',
            '3:4' => 'portrait orientation, vertical composition, tall frame',
            '9:16' => 'mobile portrait, tall vertical composition, story format',
        ];
        $aspectHint = $aspectEnhancements[$aspectRatio] ?? 'standard composition';
        return $userPrompt . ', ' . $aspectHint . ', ' . implode(', ', $qualityKeywords);
    }

    private function mockGenerateCreativeImage($generation, $prompt)
    {
        try {
            if (empty($generation->uploaded_image) || !file_exists(public_path($generation->uploaded_image))) {
                $seed = rand(1000, 9999);
                $newFile = 'creative_gen_' . time() . '_' . Str::random(6) . '.jpg';
                $targetDir = public_path('upload/generated');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $remote = "https://picsum.photos/1200/800?random={$seed}";
                try {
                    $contents = @file_get_contents($remote);
                    if ($contents) {
                        file_put_contents($targetDir . DIRECTORY_SEPARATOR . $newFile, $contents);
                    } else {
                        copy(public_path('placeholder.jpg'), $targetDir . DIRECTORY_SEPARATOR . $newFile);
                    }
                } catch (\Exception $e) {
                    copy(public_path('placeholder.jpg'), $targetDir . DIRECTORY_SEPARATOR . $newFile);
                }
                return '/upload/generated/' . $newFile;
            }

            $relativePath = str_replace(['\\', '//'], '/', $generation->uploaded_image);
            $sourcePath = public_path($relativePath);

            if (!file_exists($sourcePath)) {
                Log::error('Mock generation: source file not found', ['source' => $sourcePath]);
                return null;
            }

            $targetDir = public_path('upload/generated');
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $newFile = 'creative_gen_' . time() . '_' . Str::random(8) . '.jpg';
            $targetPath = $targetDir . DIRECTORY_SEPARATOR . $newFile;

            if (!@copy($sourcePath, $targetPath)) {
                Log::error('Mock generation: copy failed', ['from' => $sourcePath, 'to' => $targetPath]);
                return null;
            }

            return '/upload/generated/' . $newFile;
        } catch (\Exception $e) {
            Log::error('Mock generate crash: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return null;
        }
    }

    /**
     * Status for a generation.
     */
    public function getStatus($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);
            return response()->json([
                'success' => true,
                'generation' => $generation,
                'is_completed' => $generation->status === 'completed',
                'is_processing' => $generation->status === 'processing',
                'is_failed' => $generation->status === 'failed',
                'status' => $generation->status,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
    }

    /**
     * Download generated image file.
     */
    public function downloadImage($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);
            if ($generation->status !== 'completed') {
                abort(400, 'Generation not completed');
            }
            $images = $generation->generated_images ?? [];
            $first = $images[0] ?? null;
            if (!$first) {
                abort(404, 'No image found');
            }

            // first is '/upload/generated/file.jpg' style path
            $filePath = public_path(trim($first, '/'));
            if (!file_exists($filePath)) {
                abort(404, 'Image not found on disk');
            }

            return response()->download($filePath, "creative_ai_{$generation->id}_" . now()->format('YmdHis') . '.jpg');
        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage());
            abort(500, 'Failed to download');
        }
    }

    /**
     * Delete a generation.
     */
    public function destroy($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);
            // optionally delete files from disk
            if ($generation->generated_images) {
                foreach ($generation->generated_images as $img) {
                    $path = public_path(trim($img, '/'));
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
            $generation->delete();
            return response()->json(['success' => true, 'message' => 'Deleted']);
        } catch (\Exception $e) {
            Log::error('Destroy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
        }
    }

    public function history()
    {
        try {
            $gens = CreativeAIGeneration::forUser(Auth::id())->latest()->paginate(12);
            return response()->json(['success' => true, 'generations' => $gens]);
        } catch (\Exception $e) {
            Log::error('History error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load history'], 500);
        }
    }
}
