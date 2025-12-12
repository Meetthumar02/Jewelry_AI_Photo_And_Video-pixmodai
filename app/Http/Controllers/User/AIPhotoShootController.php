<?php

namespace App\Http\Controllers\User;

use App\Models\User\AIPhotoShoot;
use App\Models\User\ModelDesign;
use App\Models\User\Style;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AIPhotoShootController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $isSubscribed = (bool) ($user->is_subscribed ?? false);

        $previousShoots = AIPhotoShoot::forUser($userId)->latest()->take(10)->get();

        // Get model designs from database
        $modelDesigns = $this->getModelDesigns();

        // Get styles (industries, categories, product types, shoot types) from database
        $industries = $this->getIndustries();
        $categories = $this->getCategories();
        $productTypes = $this->getProductTypes();
        $shootTypes = $this->getShootTypes();

        return view('user.ai_studio', compact(
            'isSubscribed',
            'previousShoots',
            'modelDesigns',
            'industries',
            'categories',
            'productTypes',
            'shootTypes'
        ));
    }

    /**
     * Get model designs from database
     * Falls back to hardcoded data if table doesn't exist or is empty
     */
    private function getModelDesigns()
    {
        try {
            $designs = ModelDesign::active()->ordered()->get();

            if ($designs->isEmpty()) {
                // Fallback to hardcoded data if table is empty
                return $this->getDefaultModelDesigns();
            }

            return $designs->map(function ($design) {
                return [
                    'id' => (string) $design->id,
                    'name' => $design->name,
                    'thumbnail' => asset($design->thumbnail),
                    'category' => $design->category,
                ];
            })->toArray();
        } catch (\Exception $e) {
            // If table doesn't exist, return default data
            return $this->getDefaultModelDesigns();
        }
    }

    /**
     * Default hardcoded model designs (fallback)
     */
    private function getDefaultModelDesigns()
    {
        return [
            ['id' => 'classic_model_1', 'name' => 'Classic Model 1', 'thumbnail' => 'https://picsum.photos/id/237/200/300', 'category' => 'classic'],
            ['id' => 'classic_model_2', 'name' => 'Classic Model 2', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'classic'],
            ['id' => 'lifestyle_model_1', 'name' => 'Lifestyle Model 1', 'thumbnail' => 'https://picsum.photos/200/300?grayscale', 'category' => 'lifestyle'],
            ['id' => 'lifestyle_model_2', 'name' => 'Lifestyle Model 2', 'thumbnail' => 'https://picsum.photos/200/300/?blur', 'category' => 'lifestyle'],
            ['id' => 'luxury_model_1', 'name' => 'Luxury Model 1', 'thumbnail' => 'https://picsum.photos/id/870/200/300?grayscale&blur=2', 'category' => 'luxury'],
            ['id' => 'luxury_model_2', 'name' => 'Luxury Model 2', 'thumbnail' => 'https://picsum.photos/id/237/200/300', 'category' => 'luxury'],
            ['id' => 'outdoor_model_1', 'name' => 'Outdoor Model 1', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'outdoor'],
            ['id' => 'outdoor_model_2', 'name' => 'Outdoor Model 2', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'outdoor'],
        ];
    }

    /**
     * Get industries from database
     */
    private function getIndustries()
    {
        try {
            $industries = Style::active()->byType('industry')->ordered()->get();

            if ($industries->isEmpty()) {
                return $this->getDefaultIndustries();
            }

            return $industries->map(function ($style) {
                return [
                    'value' => $style->value,
                    'name' => $style->name,
                    'image' => $style->image_path ? asset($style->image_path) : null,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDefaultIndustries();
        }
    }

    /**
     * Get categories from database
     */
    private function getCategories()
    {
        try {
            $categories = Style::active()->byType('category')->ordered()->get();

            if ($categories->isEmpty()) {
                return $this->getDefaultCategories();
            }

            return $categories->map(function ($style) {
                return [
                    'value' => $style->value,
                    'name' => $style->name,
                    'image' => $style->image_path ? asset($style->image_path) : null,
                    'parent_id' => $style->parent_id,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDefaultCategories();
        }
    }

    /**
     * Get product types from database
     */
    private function getProductTypes()
    {
        try {
            $productTypes = Style::active()->byType('product_type')->ordered()->get();

            if ($productTypes->isEmpty()) {
                return $this->getDefaultProductTypes();
            }

            return $productTypes->map(function ($style) {
                return [
                    'value' => $style->value,
                    'name' => $style->name,
                    'image' => $style->image_path ? asset($style->image_path) : null,
                    'parent_id' => $style->parent_id,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDefaultProductTypes();
        }
    }

    /**
     * Default industries (fallback)
     */
    private function getDefaultIndustries()
    {
        return [
            ['value' => 'Jewellery', 'name' => 'Jewellery', 'image' => null],
            ['value' => 'Fashion', 'name' => 'Fashion', 'image' => null],
            ['value' => 'Accessories', 'name' => 'Accessories', 'image' => null],
        ];
    }

    /**
     * Default categories (fallback)
     */
    private function getDefaultCategories()
    {
        return [
            ['value' => 'Women Jewellery', 'name' => 'Women Jewellery', 'image' => null, 'parent_id' => null],
            ['value' => 'Men Jewellery', 'name' => 'Men Jewellery', 'image' => null, 'parent_id' => null],
            ['value' => 'Kids Jewellery', 'name' => 'Kids Jewellery', 'image' => null, 'parent_id' => null],
        ];
    }

    /**
     * Default product types (fallback)
     */
    private function getDefaultProductTypes()
    {
        return [
            ['value' => 'Necklace', 'name' => 'Necklace', 'image' => null, 'parent_id' => null],
            ['value' => 'Earrings', 'name' => 'Earrings', 'image' => null, 'parent_id' => null],
            ['value' => 'Ring', 'name' => 'Ring', 'image' => null, 'parent_id' => null],
            ['value' => 'Bracelet', 'name' => 'Bracelet', 'image' => null, 'parent_id' => null],
            ['value' => 'Pendant', 'name' => 'Pendant', 'image' => null, 'parent_id' => null],
            ['value' => 'Mangalsutra', 'name' => 'Mangalsutra', 'image' => null, 'parent_id' => null],
        ];
    }

    /**
     * Get shoot types from database
     */
    private function getShootTypes()
    {
        try {
            $shootTypes = Style::active()->byType('shoot_type')->ordered()->get();

            if ($shootTypes->isEmpty()) {
                return $this->getDefaultShootTypes();
            }

            return $shootTypes->map(function ($style) {
                return [
                    'value' => $style->value,
                    'name' => $style->name,
                    'image' => $style->image_path ? asset($style->image_path) : null,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getDefaultShootTypes();
        }
    }

    /**
     * Default shoot types (fallback)
     */
    private function getDefaultShootTypes()
    {
        return [
            ['value' => 'classic', 'name' => 'Classic', 'image' => null],
            ['value' => 'lifestyle', 'name' => 'Lifestyle', 'image' => null],
            ['value' => 'luxury', 'name' => 'Luxury', 'image' => null],
            ['value' => 'outdoor', 'name' => 'Outdoor', 'image' => null],
        ];
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        try {
            $file = $request->file('image');

            $size = $file->getSize();
            $mime = $file->getMimeType();

            $filename = 'photoshoot_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('upload/uploads');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $file->move($destinationPath, $filename);

            $relativePath = 'upload/uploads/' . $filename;

            return response()->json([
                'success' => true,
                'path' => $relativePath,
                'url' => asset($relativePath),
                'filename' => $filename,
                'size' => $size,
                'mime_type' => $mime,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to upload image: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

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

        $user = Auth::user();
        $creditsNeeded = 20;

        if ($user->total_credits < $creditsNeeded) {
            return response()->json(['success' => false, 'message' => 'Not enough credits'], 400);
        }

        $shoot = AIPhotoShoot::create([
            'user_id' => $user->id,
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

        $user->decrement('total_credits', $creditsNeeded);

        $this->generatePhotos($shoot);
        $shoot->refresh();

        return response()->json(['success' => true, 'shoot' => $shoot]);
    }

    private function generatePhotos($shoot)
    {
        sleep(2);
        $uploadPath = storage_path('app/public/' . $shoot->uploaded_image);
        if (!file_exists($uploadPath)) {
            $publicPath = public_path($shoot->uploaded_image);
            if (file_exists($publicPath)) {
                $uploadPath = $publicPath;
            } else {
                $shoot->update([
                    'status' => 'failed',
                    'error_message' => 'Uploaded image not found',
                ]);
                return false;
            }
        }

        $generatedDir = public_path('upload/generated');

        if (!file_exists($generatedDir)) {
            mkdir($generatedDir, 0777, true);
        }

        $filename = 'generated_' . time() . '_' . Str::random(10) . '.jpg';
        $destination = $generatedDir . '/' . $filename;

        copy($uploadPath, $destination);

        $shoot->update([
            'generated_images' => ['upload/generated/' . $filename],
            'status' => 'completed',
        ]);

        return true;
    }

    public function downloadImage($id)
    {
        $shoot = AIPhotoShoot::where('user_id', Auth::id())->findOrFail($id);

        $filePath = public_path($shoot->generated_images[0]);

        return response()->download($filePath);
    }

    public function destroy($id)
    {
        $shoot = AIPhotoShoot::where('user_id', Auth::id())->findOrFail($id);

        if ($shoot->uploaded_image) {
            Storage::disk('public')->delete($shoot->uploaded_image);
        }

        if (is_array($shoot->generated_images)) {
            foreach ($shoot->generated_images as $img) {
                $file = public_path($img);
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        $shoot->delete();

        return response()->json(['success' => true]);
    }

    public function history()
    {
        $shoots = AIPhotoShoot::forUser(Auth::id())->latest()->paginate(12);

        return response()->json(['success' => true, 'shoots' => $shoots]);
    }
}
