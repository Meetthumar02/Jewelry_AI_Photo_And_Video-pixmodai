<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User\CreditTransaction;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Log;
use App\Mail\VerifyEmailMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // ✅ BLOCK LOGIN IF EMAIL NOT VERIFIED
            if (!Auth::user()->email_verified_at) {
                Auth::logout();
                return back()->with('error', 'Please verify your email first.');
            }

            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // public function register(Request $request)
    // {
    //     // ✅ AJAX Email Check (unchanged)
    //     if ($request->ajax()) {
    //         $exists = User::where('email', $request->email)->exists();
    //         return response()->json([
    //             'status' => $exists,
    //             'message' => $exists ? 'Email already exists.' : 'Email available.',
    //         ]);
    //     }

    //     $validated = $request->validate(
    //         [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|email|unique:users,email',
    //             'phone' => 'required|digits:10',
    //             'password' => 'required|min:8|confirmed',
    //         ],
    //         [
    //             'email.unique' => 'Email already exists.',
    //         ],
    //     );

    //     try {
    //         DB::beginTransaction();

    //         // ✅ CREATE USER
    //         $user = User::create([
    //             'name' => $validated['name'],
    //             'email' => $validated['email'],
    //             'phone' => $validated['phone'],
    //             'password' => Hash::make($validated['password']),
    //             'total_credits' => 100,
    //             'is_subscribed' => 'false',
    //         ]);

    //         // ✅ ADD DEFAULT CREDITS
    //         CreditTransaction::create([
    //             'user_id' => $user->id,
    //             'change_type' => 'add',
    //             'credits' => 100,
    //             'reference_type' => 'system',
    //             'reference_id' => null,
    //             'note' => 'Default credits added on new user registration.',
    //         ]);

    //         // ✅ GENERATE EMAIL VERIFICATION TOKEN
    //         $token = Str::random(64);
    //         $user->email_token = $token;
    //         $user->save();

    //         DB::commit();

    //         // ✅ SEND PROFESSIONAL VERIFICATION MAIL (NEW FORMAT)
    //         $verifyLink = route('verify.email', $token);

    //         Mail::to($user->email)->send(new \App\Mail\VerifyEmailMail($user, $verifyLink));

    //         return back()->with('success', 'Verification link sent to your email!');
    //         dd($verifyLink);
    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         \Log::error('REGISTER ERROR', [
    //             'message' => $e->getMessage(),
    //             'file' => $e->getFile(),
    //             'line' => $e->getLine(),
    //         ]);

    //         return back()->with('error', 'Something went wrong, please try again.')->withInput();
    //     }
    // }


    public function register(Request $request)
{
    if ($request->ajax()) {
        $exists = User::where('email', $request->email)->exists();
        return response()->json([
            'status' => $exists,
            'message' => $exists ? 'Email already exists.' : 'Email available.'
        ]);
    }

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'phone'    => 'required|digits:10',
        'password' => 'required|min:8|confirmed',
    ], [
        'email.unique' => 'Email already exists.',
    ]);

    try {
        DB::beginTransaction();

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'],
            'password'       => Hash::make($validated['password']),
            'total_credits'  => 100,
            'is_subscribed'  => 'false'
        ]);

        CreditTransaction::create([
            'user_id'        => $user->id,
            'change_type'    => 'add',
            'credits'        => 100,
            'reference_type' => 'system',
            'reference_id'   => null,
            'note'           => 'Default credits added on new user registration.',
        ]);

        $token = Str::random(64);
        $user->email_token = $token;
        $user->save();

        DB::commit();

        $verifyLink = route('verify.email', $token);

        Mail::to($user->email)->send(
            new \App\Mail\VerifyEmailMail($user, $verifyLink)
        );

        return back()->with('success', 'Verification link sent to your email!');

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('REGISTER ERROR', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
            'request' => $request->all(),
        ]);

        if (config('app.debug')) {
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

        return back()
            ->with('error', 'Something went wrong, please try again.')
            ->withInput();
    }
}


    public function verifyEmail($token)
    {
        $user = User::where('email_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        $user->email_token = null;
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Account verified successfully!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
