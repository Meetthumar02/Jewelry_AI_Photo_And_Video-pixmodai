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
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $isSubscribed = (bool) ($user->is_subscribed ?? false);

        $previousShoots = AIPhotoShoot::forUser($userId)->latest()->take(10)->get();

        $modelDesigns = $this->getModelDesigns($request);

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

    public function getModelDesigns(Request $request)
    {
        $designs = ModelDesign::with(['industry', 'category', 'productType', 'shootType'])
            ->where('industry_id', $request->industry_id)
            ->where('category_id', $request->category_id)
            ->where('product_type_id', $request->product_type_id)
            ->where('shoot_type_id', $request->shoot_type_id)
            ->get();

        return response()->json([
            'modelDesigns' => $designs->map(function ($d) {
                return [
                    'id' => $d->id,
                    'thumbnail' => $d->image ?? 'default.png',

                    'industry_name' => $d->industry->name ?? '',
                    'category_name' => $d->category->name ?? '',
                    'product_type_name' => $d->productType->name ?? '',
                    'shoot_type_name' => $d->shootType->name ?? '',
                    'prompt' => $d->prompt ?? '',
                ];
            })
        ]);
    }

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

    private function getDefaultIndustries()
    {
        return [
            ['value' => 'Jewellery', 'name' => 'Jewellery', 'image' => null],
            ['value' => 'Fashion', 'name' => 'Fashion', 'image' => null],
            ['value' => 'Accessories', 'name' => 'Accessories', 'image' => null],
        ];
    }

    private function getDefaultCategories()
    {
        return [
            ['value' => 'Women Jewellery', 'name' => 'Women Jewellery', 'image' => null, 'parent_id' => null],
            ['value' => 'Men Jewellery', 'name' => 'Men Jewellery', 'image' => null, 'parent_id' => null],
            ['value' => 'Kids Jewellery', 'name' => 'Kids Jewellery', 'image' => null, 'parent_id' => null],
        ];
    }

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

        $modelDesign = ModelDesign::find($request->model_design_id);
        $prompt = $modelDesign ? $modelDesign->prompt : '';

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
            'prompts_used' => [$prompt],
        ]);

        $user->decrement('total_credits', $creditsNeeded);

        $success = $this->generatePhotos($shoot);
        $shoot->refresh();

        if (!$success) {
            $user->increment('total_credits', $creditsNeeded);
            return response()->json([
                'success' => false,
                'message' => 'Generation failed: ' . ($shoot->error_message ?? 'Unknown error')
            ]);
        }

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
