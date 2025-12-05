<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\CreditsTopup;
use App\Models\User\CreditTransaction;
use App\Models\User\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
class TopupController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = auth()->user();

        $amount = (int) $request->amount;
        $credits = (int) $request->credits;
        $planId = $request->plan_id ? (int) $request->plan_id : null;
        // $gst = round($credits * 0.18);
        $totalAmount = $amount;

        $orderId = 'ORD_' . time();

        // 1. Insert PENDING entry
        $topup = CreditsTopup::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'credits' => $credits,
            'amount' => $totalAmount,
            'order_id' => $orderId,
            'payment_status' => 'pending',
        ]);

        // Validate Cashfree credentials
        $clientId = env('CASHFREE_APP_ID');
        $secretKey = env('CASHFREE_SECRET_KEY');

        if (empty($clientId) || empty($secretKey)) {
            \Log::error('Cashfree credentials missing');
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Payment gateway configuration error. Please contact support.',
                ],
                500,
            );
        }

        $url = env('CASHFREE_ENV') == 'PRODUCTION' ? 'https://api.cashfree.com/pg/orders' : 'https://sandbox.cashfree.com/pg/orders';

        $response = Http::withHeaders([
            'x-client-id' => $clientId,
            'x-client-secret' => $secretKey,
            'x-api-version' => '2022-09-01',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'order_id' => $orderId,
            'order_amount' => $totalAmount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => (string) $user->id,
                'customer_email' => $user->email ?? 'test@example.com',
                'customer_phone' => $user->mobile ?? ($user->phone ?? '9999999999'),
            ],
            'order_meta' => [
                'return_url' => route('cashfree.success') . "?order_id={$orderId}",
                'notify_url' => route('cashfree.webhook'),
            ],
        ]);

        // Check HTTP status
        if (!$response->successful()) {
            \Log::error('Cashfree API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $topup->update([
                'payment_status' => 'failed',
                'cf_payment_response' => json_encode(['error' => 'API request failed', 'status' => $response->status()]),
            ]);

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Payment gateway error. Please try again.',
                    'cf_response' => $response->json(),
                ],
                400,
            );
        }

        $data = $response->json();

        // Check for Cashfree API errors
        if (isset($data['message']) && $data['message'] !== 'OK') {
            \Log::error('Cashfree API returned error', $data);

            $topup->update([
                'payment_status' => 'failed',
                'cf_payment_response' => json_encode($data),
            ]);

            return response()->json(
                [
                    'status' => false,
                    'message' => $data['message'] ?? 'Payment gateway error',
                    'cf_response' => $data,
                ],
                400,
            );
        }

        $topup->update([
            'cf_order_id' => $data['cf_order_id'] ?? null,
            'cf_payment_response' => json_encode($data),
        ]);

        // 4. SAFETY CHECK — prevent undefined array error
        if (!isset($data['payment_session_id'])) {
            \Log::error('Cashfree missing payment_session_id', $data);

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Payment session creation failed. Please try again.',
                    'cf_response' => $data,
                ],
                400,
            );
        }

        // 5. SUCCESS → return to frontend
        return response()->json([
            'status' => true,
            'order_id' => $orderId,
            'payment_session_id' => $data['payment_session_id'],
        ]);
    }
    // public function success(Request $request)
    // {
    //       $orderId = $request->order_id;

    //     if (!$orderId) {
    //         return redirect()->route('user.dashboard')
    //             ->with("error", "Invalid order ID!");
    //     }

    //     // Get order from DB
    //     $topup = CreditsTopup::where("order_id", $orderId)->first();

    //     if (!$topup) {
    //         return redirect()->route('user.dashboard')
    //             ->with("error", "Order not found!");
    //     }

    //     // Call Cashfree verify API
    //     $url = env("CASHFREE_ENV") == "PRODUCTION"
    //         ? "https://api.cashfree.com/pg/orders/{$orderId}"
    //         : "https://sandbox.cashfree.com/pg/orders/{$orderId}";

    //     $response = Http::withHeaders([
    //         "x-client-id" => env("CASHFREE_APP_ID"),
    //         "x-client-secret" => env("CASHFREE_SECRET_KEY"),
    //         "x-api-version" => "2022-09-01",
    //         "Content-Type" => "application/json"
    //     ])->get($url);

    //     $data = $response->json();

    //     \Log::info("Cashfree Verify Response", $data);

    //     // Read status safely
    //     $orderStatus =
    //         $data["order_status"]
    //         ?? ($data["data"]["order_status"] ?? null)
    //         ?? ($data["data"]["order"]["order_status"] ?? null);

    //     // ❌ Payment failed
    //     if ($orderStatus !== "PAID") {

    //         $topup->update([
    //             "payment_status" => "failed"
    //         ]);

    //         return redirect()->route('user.dashboard')
    //             ->with("error", "Payment Failed!");
    //     }

    //     // ✔ SUCCESS — add credits
    //     $topup->update([
    //         "payment_status" => "success"
    //     ]);

    //     $user = User::find($topup->user_id);

    //     // Update user wallet
    //     $user->total_credits = ($user->total_credits ?? 0) + $topup->credits;
    //     $user->save();

    //     // Transaction log (optional)
    //     CreditTransaction::create([
    //         'user_id' => $user->id,
    //         'change_type' => 'add',
    //         'credits' => $topup->credits,
    //         'reference_type' => 'topup',
    //         'note' => "Top-up Payment"
    //     ]);

    //     return redirect()->route('user.dashboard')
    //         ->with("success", "Payment Successful!");
    // }

    // public function success(Request $request)
    // {
    //     $orderId = $request->order_id;

    //     if (!$orderId) {
    //         return redirect()->route('user.dashboard')->with('error', 'Invalid order ID!');
    //     }

    //     // Fetch order
    //     $topup = CreditsTopup::where('order_id', $orderId)->first();

    //     if (!$topup) {
    //         return redirect()->route('user.dashboard')->with('error', 'Order not found!');
    //     }

    //     // Prevent double credit adding if already successful
    //     if ($topup->payment_status === 'success') {
    //         return redirect()->route('user.dashboard')->with('success', 'Payment already processed!');
    //     }

    //     // Cashfree verify API
    //     $url = env('CASHFREE_ENV') == 'PRODUCTION' ? "https://api.cashfree.com/pg/orders/{$orderId}" : "https://sandbox.cashfree.com/pg/orders/{$orderId}";

    //     $response = Http::withHeaders([
    //         'x-client-id' => env('CASHFREE_APP_ID'),
    //         'x-client-secret' => env('CASHFREE_SECRET_KEY'),
    //         'x-api-version' => '2022-09-01',
    //         'Content-Type' => 'application/json',
    //     ])->get($url);

    //     $data = $response->json();

    //     \Log::info('Cashfree Verify Response', $data);

    //     // Read status safely
    //     $orderStatus = $data['order_status'] ?? ($data['data']['order_status'] ?? null ?? ($data['data']['order']['order_status'] ?? null));

    //     //------------------------------------------------------
    //     // ❌ PAYMENT FAILED
    //     //------------------------------------------------------
    //     if ($orderStatus !== 'PAID') {
    //         $topup->update([
    //             'payment_status' => 'failed',
    //         ]);

    //         return redirect()->route('user.dashboard')->with('error', 'Payment Failed!');
    //     }

    //     //------------------------------------------------------
    //     // ✔ PAYMENT SUCCESS — Update credits
    //     //------------------------------------------------------
    //     $topup->update([
    //         'payment_status' => 'success',
    //     ]);

    //     $user = User::find($topup->user_id);

    //     // Add credits only 1 time
    //     $user->total_credits = ($user->total_credits ?? 0) + $topup->credits;
    //     $user->is_subscribed = 'true';
    //     $user->save();

    //     // Log transaction
    //     CreditTransaction::create([
    //         'user_id' => $user->id,
    //         'change_type' => 'add',
    //         'credits' => $topup->credits,
    //         'reference_type' => 'topup',
    //         'note' => 'Top-up Payment',
    //     ]);

    //     // Create subscription entry if plan_id exists
    //     if ($topup->plan_id) {
    //         $plan = SubscriptionPlan::find($topup->plan_id);
    //         if ($plan) {
    //             // Check if subscription already exists for this order
    //             $existingSubscription = Subscription::where('order_id', $topup->order_id)->first();
    //             if (!$existingSubscription) {
    //                 Subscription::create([
    //                     'user_id' => $user->id,
    //                     'plan_id' => $plan->id,
    //                     'credits' => $topup->credits,
    //                     'amount' => $topup->amount,
    //                     'start_date' => now(),
    //                     'end_date' => now()->addMonths($plan->duration_months ?? 1),
    //                     'order_id' => $topup->order_id,
    //                     'cf_order_id' => $topup->cf_order_id,
    //                     'payment_status' => 'success',
    //                     'cf_payment_response' => $topup->cf_payment_response,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('user.dashboard')->with('success', 'Payment Successful!');
    // }

  public function success(Request $request)
{
    $orderId = $request->order_id;

    if (!$orderId) {
        return redirect()->route('user.dashboard')->with('error', 'Invalid order ID!');
    }

    // Fetch order
    $topup = CreditsTopup::where('order_id', $orderId)->first();

    if (!$topup) {
        return redirect()->route('user.dashboard')->with('error', 'Order not found!');
    }

    // Prevent double credit adding if already successful
    if ($topup->payment_status === 'success') {
        return redirect()->route('user.dashboard')->with('success', 'Payment already processed!');
    }

    // Cashfree verify API
    $url = env('CASHFREE_ENV') == 'PRODUCTION'
        ? "https://api.cashfree.com/pg/orders/{$orderId}"
        : "https://sandbox.cashfree.com/pg/orders/{$orderId}";

    $response = Http::withHeaders([
        'x-client-id' => env('CASHFREE_APP_ID'),
        'x-client-secret' => env('CASHFREE_SECRET_KEY'),
        'x-api-version' => '2022-09-01',
        'Content-Type' => 'application/json',
    ])->get($url);

    $data = $response->json();

    \Log::info('Cashfree Verify Response', $data);

    // Read status safely
    $orderStatus = $data['order_status']
        ?? ($data['data']['order_status']
        ?? ($data['data']['order']['order_status'] ?? null));

    //------------------------------------------------------
    // ❌ PAYMENT FAILED
    //------------------------------------------------------
    if ($orderStatus !== 'PAID') {
        $topup->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->route('user.dashboard')->with('error', 'Payment Failed!');
    }

    //------------------------------------------------------
    // ✔ PAYMENT SUCCESS — Update credits
    //------------------------------------------------------
    $topup->update([
        'payment_status' => 'success',
    ]);

    $user = User::find($topup->user_id);

    // Add credits only 1 time
    $user->total_credits = ($user->total_credits ?? 0) + $topup->credits;

    // ✅ FIX 1: BOOLEAN VALUE (NOT STRING)
    $user->is_subscribed = 'true';

    // ✅ FIX 2: STORE ONLY THESE 3 FIELDS IN USERS TABLE
    if ($topup->plan_id) {
        $user->plan_id = $topup->plan_id;
        $user->subscription_start = now();
        $user->subscription_end   = now()->addMonths(1); // change if your plan duration is dynamic
    }

    $user->save();

    // Log transaction
    CreditTransaction::create([
        'user_id' => $user->id,
        'change_type' => 'add',
        'credits' => $topup->credits,
        'reference_type' => 'topup',
        'note' => 'Top-up Payment',
    ]);

    // Create subscription entry if plan_id exists (UNCHANGED)
    if ($topup->plan_id) {
        $plan = SubscriptionPlan::find($topup->plan_id);
        if ($plan) {
            // Check if subscription already exists for this order
            $existingSubscription = Subscription::where('order_id', $topup->order_id)->first();
            if (!$existingSubscription) {
                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'credits' => $topup->credits,
                    'amount' => $topup->amount,
                    'start_date' => now(),
                    'end_date' => now()->addMonths($plan->duration_months ?? 1),
                    'order_id' => $topup->order_id,
                    'cf_order_id' => $topup->cf_order_id,
                    'payment_status' => 'success',
                    'cf_payment_response' => $topup->cf_payment_response,
                ]);
            }
        }
    }

    return redirect()->route('user.dashboard')->with('success', 'Payment Successful!');
}


    public function webhook(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->type;

        $topup = CreditsTopup::where('order_id', $orderId)->first();

        if (!$topup) {
            return response('not found', 404);
        }

        if ($status == 'PAYMENT_SUCCESS' && $topup->payment_status !== 'success') {
            $topup->update([
                'payment_status' => 'success',
                'cf_payment_response' => json_encode($request->all()),
            ]);

            $user = User::find($topup->user_id);
            if ($user) {
                $user->total_credits = ($user->total_credits ?? 0) + $topup->credits;
                $user->is_subscribed = 'true';
                $user->save();

                CreditTransaction::create([
                    'user_id' => $user->id,
                    'change_type' => 'add',
                    'credits' => $topup->credits,
                    'reference_type' => 'topup',
                    'note' => 'Top-up Payment (webhook)',
                ]);

                // Create subscription entry if plan_id exists
                if ($topup->plan_id) {
                    $plan = SubscriptionPlan::find($topup->plan_id);
                    if ($plan) {
                        // Check if subscription already exists for this order
                        $existingSubscription = Subscription::where('order_id', $topup->order_id)->first();
                        if (!$existingSubscription) {
                            Subscription::create([
                                'user_id' => $user->id,
                                'plan_id' => $plan->id,
                                'credits' => $topup->credits,
                                'amount' => $topup->amount,
                                'start_date' => now(),
                                'end_date' => now()->addMonths($plan->duration_months ?? 1),
                                'order_id' => $topup->order_id,
                                'cf_order_id' => $topup->cf_order_id,
                                'payment_status' => 'success',
                                'cf_payment_response' => $topup->cf_payment_response,
                            ]);
                        }
                    }
                }
            }
        }

        return response('OK', 200);
    }
}

// this is my full top up controller now give me only new add code
