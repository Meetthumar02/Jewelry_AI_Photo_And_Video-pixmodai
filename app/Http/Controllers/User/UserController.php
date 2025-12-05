<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\CatalogStudio;
use App\Models\User\AIPhotoShoot;
use App\Models\User\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $userId = $user->id;

        $favoriteCount = Favorite::where('user_id', $userId)->count();

        $catalogCount = CatalogStudio::where('user_id', $userId)->count();

        $recentLibrary = CatalogStudio::where('user_id', $userId)->orderBy('created_at', 'DESC')->limit(6)->get();

        return view('user.dashboard', compact('user', 'favoriteCount', 'catalogCount', 'recentLibrary'));
    }

  public function catalogLibrary(Request $request)
{
    $userId = Auth::id();

    $query = CatalogStudio::where('user_id', $userId)
        ->with('photos'); // ai_photo_shoots images

    if ($request->filled('type')) {
        $query->where('jewelry_type', $request->type);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', "%$search%")
              ->orWhere('metal_type', 'like', "%$search%")
              ->orWhere('jewelry_type', 'like', "%$search%");
        });
    }

    $query->orderBy('id', $request->sort == 'oldest' ? 'asc' : 'desc');

    $catalogs = $query->paginate(8)->withQueryString();

    $favoriteIds = Favorite::where('user_id', $userId)
        ->pluck('catalog_studio_id')
        ->toArray();

    return view('user.catalog_library', compact('catalogs', 'favoriteIds'));
}



    public function generationHistory(Request $request)
    {
        $userId = Auth::id();

        $query = \DB::table('ai_photo_shoots')->where('user_id', $userId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_type', 'like', "%$search%")
                    ->orWhere('metal_type', 'like', "%$search%")
                    ->orWhere('shoot_type', 'like', "%$search%")
                    ->orWhere('mode_type', 'like', "%$search%");
            });
        }

        if ($request->filled('type')) {
            $query->where('product_type', $request->type);
        }

        if ($request->filled('mode')) {
            $query->where('mode_type', $request->mode);
        }

        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $generations = $query->paginate(12);

        return view('user.generation_history', compact('generations'));
    }

    public function favorites(Request $request)
    {
        $userId = Auth::id();

        // Get favorited catalog studios
        $favoriteIds = Favorite::where('user_id', $userId)->pluck('catalog_studio_id');

        $query = CatalogStudio::whereIn('id', $favoriteIds);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%$search%")
                    ->orWhere('metal_type', 'like', "%$search%")
                    ->orWhere('jewelry_type', 'like', "%$search%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('jewelry_type', $request->type);
        }

        // Sort
        if ($request->sort == 'oldest') {
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $favorites = $query->paginate(8);

        return view('user.favorites', compact('favorites'));
    }

    /**
     * Toggle favorite status for a catalog item
     */
  public function toggleFavorite(Request $request)
{
    $favorite = Favorite::where('user_id', auth()->id())
        ->where('catalog_studio_id', $request->catalog_id)
        ->first();

    if ($favorite) {
        $favorite->delete();
        return response()->json([
            'success' => true,
            'is_favorite' => false
        ]);
    } else {
        Favorite::create([
            'user_id' => auth()->id(),
            'catalog_studio_id' => $request->catalog_id
        ]);

        return response()->json([
            'success' => true,
            'is_favorite' => true
        ]);
    }
}


    public function contact()
    {
        return view('user.contact');
    }

    public function updateAiSettings(Request $request)
    {
        $request->validate([
            'ai_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100',
            'remove_logo' => 'nullable|in:0,1',
        ]);

        $user = Auth::user();

        // User folder: public/upload/john-gmail-com/
        $emailSlug = Str::slug($user->email);
        $userFolder = public_path("upload/$emailSlug");

        // Create folder if missing
        File::ensureDirectoryExists($userFolder);

        /* -----------------------------------------
         *   REMOVE LOGO
         * ----------------------------------------- */
        if ($request->remove_logo == '1') {
            if (!empty($user->brand_logo_path)) {
                $old = $userFolder . '/' . $user->brand_logo_path;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $user->brand_logo_path = null;
            $user->save();

            return back()->with('status', 'Logo removed successfully.');
        }

        /* -----------------------------------------
         *   UPLOAD NEW LOGO
         * ----------------------------------------- */
        if ($request->hasFile('ai_logo')) {
            // Delete existing logo
            if (!empty($user->brand_logo_path)) {
                $old = $userFolder . '/' . $user->brand_logo_path;
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $file = $request->file('ai_logo');

            // Original filename
            $original = $file->getClientOriginalName();

            // Sanitize filename
            $safeName = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $original);

            // Move to user folder
            $file->move($userFolder, $safeName);

            // Save only the filename
            $user->brand_logo_path = $safeName;
            $user->save();

            return back()->with('status', 'Logo updated successfully.');
        }

        return back()->with('status', 'No changes made.');
    }
    public function index()
    {
        $userId = auth()->id();

        // fetch recent catalog designs (latest 8)
        $recentLibrary = \App\Models\Catalogue::where('user_id', $userId)->orderBy('created_at', 'DESC')->take(8)->get();

        // existing stats
        $favoriteCount = Favorite::where('user_id', $userId)->count();
        $catalogCount = Catalogue::where('user_id', $userId)->count();

        return view('user.dashboard', compact('recentLibrary', 'favoriteCount', 'catalogCount'));
    }
}
