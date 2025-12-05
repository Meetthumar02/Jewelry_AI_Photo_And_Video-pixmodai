<?php

namespace App\Http\Controllers\User;

use App\Models\User\CreativeAIGeneration;
use App\Models\User\CreditTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreativeAIController extends Controller
{
    const CREDITS_PER_GENERATION = 20;
    const MAX_FILE_SIZE = 10485760;
    const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];

    public function index()
    {
        $modelDesigns = [
            ['id'=>'classic_model_1','name'=>'Classic Model 1','thumbnail'=>'https://picsum.photos/id/237/200/300','category'=>'classic'],
            ['id'=>'classic_model_2','name'=>'Classic Model 2','thumbnail'=>'https://picsum.photos/seed/picsum/200/300','category'=>'classic'],
            ['id'=>'classic_model_1','name'=>'Classic Model 1','thumbnail'=>'https://picsum.photos/id/237/200/300','category'=>'classic'],
            ['id'=>'classic_model_2','name'=>'Classic Model 2','thumbnail'=>'https://picsum.photos/seed/picsum/200/300','category'=>'classic'],
            ['id'=>'lifestyle_model_1','name'=>'Lifestyle Model 1','thumbnail'=>'https://picsum.photos/200/300?grayscale','category'=>'lifestyle'],
            ['id'=>'lifestyle_model_2','name'=>'Lifestyle Model 2','thumbnail'=>'https://picsum.photos/200/300/?blur','category'=>'lifestyle'],
            ['id'=>'luxury_model_1','name'=>'Luxury Model 1','thumbnail'=>'https://picsum.photos/id/870/200/300?grayscale&blur=2','category'=>'luxury'],
            ['id'=>'luxury_model_2','name'=>'Luxury Model 2','thumbnail'=>'https://picsum.photos/id/237/200/300','category'=>'luxury'],
            ['id'=>'outdoor_model_1','name'=>'Outdoor Model 1','thumbnail'=>'https://picsum.photos/seed/picsum/200/300','category'=>'outdoor'],
            ['id'=>'outdoor_model_2','name'=>'Outdoor Model 2','thumbnail'=>'https://picsum.photos/seed/picsum/200/300','category'=>'outdoor'],
        ];
        return view('user.ai_studio', compact('modelDesigns'));
    }

    public function uploadImage(Request $request)
    {
        try {
            Log::info('UPLOAD API HIT');

            if (!$request->hasFile('image')) {
                Log::error('NO FILE RECEIVED');
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'No file received',
                    ],
                    422,
                );
            }

            $file = $request->file('image');

            Log::info('FILE RECEIVED', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);

            $filename = 'creative_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            $destination = public_path('upload');

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
                Log::info('UPLOAD FOLDER CREATED');
            }

            $file->move($destination, $filename);

            $path = 'upload/' . $filename;

            Log::info('UPLOAD SUCCESS', [
                'path' => $path,
                'url' => asset($path),
            ]);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset($path),
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error('UPLOAD CRASH', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Upload failed',
                ],
                500,
            );
        }
    }

    public function generate(Request $request)
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|min:10|max:2000',
                'uploaded_image' => 'required|string',
                'aspect_ratio' => 'required|string|in:1:1,4:3,16:9,3:4,9:16',
                'output_format' => 'required|string|in:JPEG,PNG',
            ]);

            $userId = Auth::id();
            $user = Auth::user();
            $creditsNeeded = self::CREDITS_PER_GENERATION;

            if ($user->total_credits < $creditsNeeded) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Insufficient credits',
                    ],
                    400,
                );
            }

            $generation = CreativeAIGeneration::create([
                'user_id' => $userId,
                'prompt' => $validated['prompt'],
                'uploaded_image' => $validated['uploaded_image'],
                'aspect_ratio' => $validated['aspect_ratio'],
                'output_format' => $validated['output_format'],
                'status' => 'processing',
                'credits_used' => $creditsNeeded,
            ]);

            $user->total_credits -= $creditsNeeded;
            $user->save();

            CreditTransaction::create([
                'user_id' => $userId,
                'change_type' => 'use',
                'credits' => -$creditsNeeded,
                'reference_type' => 'creative_ai',
                'reference_id' => $generation->id,
                'note' => 'Creative AI Generation',
            ]);

            Log::info('Creative AI Generation Started', [
                'generation_id' => $generation->id,
                'user_id' => $userId,
                'credits_used' => $creditsNeeded,
            ]);

            $generation->markAsProcessing();

            $enhancedPrompt = $this->enhancePrompt($generation->prompt, $generation->aspect_ratio);

            $generatedImagePath = $this->mockGenerateCreativeImage($generation, $enhancedPrompt);

            if (!$generatedImagePath || !is_string($generatedImagePath)) {
                $user->total_credits += $creditsNeeded;
                $user->save();

                CreditTransaction::create([
                    'user_id' => $userId,
                    'change_type' => 'refund',
                    'credits' => $creditsNeeded,
                    'reference_type' => 'creative_ai',
                    'reference_id' => $generation->id,
                    'note' => 'Refund - Creative AI Generation Failed',
                ]);

                $generation->markAsFailed('Mock generation returned invalid image');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Image generation failed. Credits refunded.',
                    ],
                    500,
                );
            }

            $generation->markAsCompleted(
                [$generatedImagePath],
                [
                    'prompt' => $generation->prompt,
                    'service' => 'Mock Service',
                    'time' => now()->toDateTimeString(),
                ],
            );

            $generation->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Image generated successfully!',
                'generation_id' => $generation->id,
                'generation' => $generation,
                'credits_remaining' => $user->total_credits,
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            Log::error('Creative AI Generation Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (isset($generation)) {
                $generation->markAsFailed($e->getMessage());
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to generate image. Please try again.',
                ],
                500,
            );
        }
    }

    private function generateCreativeImage($generation)
    {
        try {
            $generation->markAsProcessing();

            $enhancedPrompt = $this->enhancePrompt($generation->prompt, $generation->aspect_ratio);
            sleep(2);

            $generatedImagePath = $this->mockGenerateCreativeImage($generation, $enhancedPrompt);

            if (!$generatedImagePath) {
                throw new \Exception('Failed to generate image from AI service.');
            }

            $aiResponse = [
                'enhanced_prompt' => $enhancedPrompt,
                'original_prompt' => $generation->prompt,
                'aspect_ratio' => $generation->aspect_ratio,
                'output_format' => $generation->output_format,
                'timestamp' => now()->toIso8601String(),
                'service' => 'Mock AI Service',
            ];

            $generation->markAsCompleted([$generatedImagePath], $aiResponse);

            return true;
        } catch (\Exception $e) {
            Log::error('Creative AI Image Generation Failed', [
                'generation_id' => $generation->id,
                'error' => $e->getMessage(),
            ]);

            $generation->markAsFailed($e->getMessage());

            return false;
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
        $qualityString = implode(', ', $qualityKeywords);

        return "{$userPrompt}, {$aspectHint}, {$qualityString}";
    }

    private function mockGenerateCreativeImage($generation, $prompt)
    {
        try {
            Log::info('=== MOCK GENERATION START ===');

            Log::info('Generation Data:', [
                'id' => $generation->id,
                'uploaded_image' => $generation->uploaded_image,
            ]);

            if (!$generation->uploaded_image) {
                Log::error('❌ uploaded_image IS NULL');
                return null;
            }

            $relativePath = str_replace(['\\', '//'], '/', $generation->uploaded_image);
            $sourcePath = public_path($relativePath);

            Log::info('SOURCE PATH CHECK:', [
                'relative' => $relativePath,
                'absolute' => $sourcePath,
            ]);

            if (!file_exists($sourcePath)) {
                Log::error('❌ FILE NOT FOUND', [
                    'path' => $sourcePath,
                ]);
                return null;
            }

            $targetDir = public_path('upload/generated');

            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
                Log::info('GENERATED DIRECTORY CREATED');
            }

            $newFile = 'creative_gen_' . time() . '_' . Str::random(8) . '.jpg';
            $targetPath = $targetDir . DIRECTORY_SEPARATOR . $newFile;

            Log::info('COPY START', [
                'from' => $sourcePath,
                'to' => $targetPath,
            ]);

            if (!copy($sourcePath, $targetPath)) {
                Log::error('❌ FILE COPY FAILED');
                return null;
            }

            $publicUrl = '/upload/generated/' . $newFile;

            Log::info('✅ MOCK GENERATION SUCCESS', [
                'url' => $publicUrl,
            ]);

            return $publicUrl;
        } catch (\Exception $e) {
            Log::error('🔥 MOCK GENERATION CRASH', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    public function getStatus($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);

            return response()->json([
                'success' => true,
                'generation' => $generation,
                'is_completed' => $generation->isCompleted(),
                'is_processing' => $generation->isProcessing(),
                'is_failed' => $generation->isFailed(),
                'status' => $generation->status,
                'status_label' => $generation->status_label,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Generation not found.',
                ],
                404,
            );
        }
    }

    public function downloadImage($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);

            if (!$generation->isCompleted()) {
                abort(400, 'Generation not completed yet. Please wait.');
            }

            $firstImage = $generation->first_image;

            if (!$firstImage) {
                abort(404, 'No generated image found.');
            }

            $filePath = str_replace('/storage/', '', $firstImage);

            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'Image file not found on server.');
            }

            $filename = "creative_ai_gen_{$generation->id}_" . now()->format('YmdHis') . '.jpg';

            Log::info('Creative AI Image Downloaded', [
                'generation_id' => $generation->id,
                'user_id' => Auth::id(),
            ]);

            return Storage::disk('public')->download($filePath, $filename);
        } catch (\Exception $e) {
            Log::error('Creative AI Download Error: ' . $e->getMessage());
            abort(500, 'Failed to download image.');
        }
    }

    public function destroy($id)
    {
        try {
            $generation = CreativeAIGeneration::where('user_id', Auth::id())->findOrFail($id);

            $generation->delete();

            Log::info('Creative AI Generation Deleted', [
                'generation_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Generation deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Creative AI Delete Error: ' . $e->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to delete generation.',
                ],
                500,
            );
        }
    }

    public function history()
    {
        try {
            $generations = CreativeAIGeneration::forUser(Auth::id())->latest()->paginate(12);

            return response()->json([
                'success' => true,
                'generations' => $generations,
            ]);
        } catch (\Exception $e) {
            Log::error('Creative AI History Error: ' . $e->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to load history.',
                ],
                500,
            );
        }
    }
}
