<?php

namespace App\Http\Controllers\User;

use App\Models\User\AIPhotoShoot;
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

        $isSubscribed = strtolower($user->is_subscribed ?? 'false') === 'true';

        $previousShoots = AIPhotoShoot::forUser($userId)->latest()->take(10)->get();

        $modelDesigns = $this->getModelDesigns();

        return view('user.ai_studio', compact('isSubscribed', 'previousShoots', 'modelDesigns'));
    }

    private function getModelDesigns()
    {
        return [
            ['id' => 'classic_model_1', 'name' => 'Classic Model 1', 'thumbnail' => 'https://picsum.photos/id/237/200/300', 'category' => 'classic'],
            ['id' => 'classic_model_2', 'name' => 'Classic Model 2', 'thumbnail' => 'https://picsum.photos/seed/picsum/200/300', 'category' => 'classic'],
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
