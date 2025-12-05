<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AiSettingController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('ai-settings'));
    }

    public function updateLogo(Request $request)
    {
        try {
            $request->validate([
                'ai_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'remove_logo' => 'nullable|in:0,1',
            ], [
                'ai_logo.image' => 'The file must be an image.',
                'ai_logo.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
                'ai_logo.max' => 'The image may not be greater than 1MB.',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return back()->with('error', 'User not found. Please login again.');
            }

            $emailSlug = Str::slug($user->email ?? 'user');
            $folder = public_path("upload/$emailSlug");
            
            // Ensure directory exists
            if (!File::isDirectory($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            // Handle logo removal
            if ($request->remove_logo === '1' && $user->brand_logo_path) {
                $existing = $folder . '/' . $user->brand_logo_path;
                if (File::exists($existing)) {
                    File::delete($existing);
                }
                $user->brand_logo_path = null;
                $user->save();
                return back()->with('status', 'Logo removed successfully.');
            }

            // Handle logo upload
            if ($request->hasFile('ai_logo')) {
                $file = $request->file('ai_logo');
                
                // Validate file
                if (!$file->isValid()) {
                    return back()->with('error', 'Invalid file. Please try again.');
                }

                // Delete old logo if exists
                if ($user->brand_logo_path) {
                    $existing = $folder . '/' . $user->brand_logo_path;
                    if (File::exists($existing)) {
                        File::delete($existing);
                    }
                }

                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
                
                // Ensure unique filename if file already exists
                $counter = 1;
                $baseName = pathinfo($safeName, PATHINFO_FILENAME);
                $extension = pathinfo($safeName, PATHINFO_EXTENSION);
                while (File::exists($folder . '/' . $safeName)) {
                    $safeName = $baseName . '_' . $counter . '.' . $extension;
                    $counter++;
                }
                
                // Move file
                try {
                    $moved = $file->move($folder, $safeName);
                    
                    if ($moved && File::exists($folder . '/' . $safeName)) {
                        $user->brand_logo_path = $safeName;
                        $user->save();
                        
                        \Log::info('Logo uploaded successfully', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'logo_path' => $user->brand_logo_path,
                            'file_path' => $folder . '/' . $safeName,
                            'file_size' => File::size($folder . '/' . $safeName)
                        ]);
                        
                        return back()->with('status', 'Logo uploaded successfully!');
                    } else {
                        \Log::error('Logo file move failed', [
                            'user_id' => $user->id,
                            'folder' => $folder,
                            'filename' => $safeName
                        ]);
                        return back()->with('error', 'Failed to save logo file. Please check folder permissions.');
                    }
                } catch (\Exception $e) {
                    \Log::error('Logo upload exception', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return back()->with('error', 'Error uploading logo: ' . $e->getMessage());
                }
            }

            return back()->with('status', 'No changes made.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Logo upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

