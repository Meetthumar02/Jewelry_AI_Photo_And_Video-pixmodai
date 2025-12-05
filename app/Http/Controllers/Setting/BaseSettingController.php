<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User\CreditsTopup;
use App\Models\User\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

abstract class BaseSettingController extends Controller
{
    protected function sharedData(string $activeTab): array
    {
        $user = Auth::user();
        $emailSlug = $user ? Str::slug($user->email ?? 'user') : 'user';
        $brandLogoUrl = $user && $user->brand_logo_path ? asset("upload/$emailSlug/" . $user->brand_logo_path) : null;

        $name = trim($user->name ?? 'User');
        $nameParts = preg_split('/\s+/', $name);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
        $initial = strtoupper(substr($name, 0, 1));

        $settingsMenu = [
            ['id' => 'account', 'label' => 'Account', 'icon' => 'fas fa-user', 'route' => route('settings.account')],
            ['id' => 'ai-settings', 'label' => 'AI Settings', 'icon' => 'fas fa-robot', 'route' => route('settings.ai')],
            ['id' => 'notifications', 'label' => 'Notifications', 'icon' => 'fas fa-bell', 'route' => route('settings.notifications')],
            ['id' => 'security', 'label' => 'Security', 'icon' => 'fas fa-shield-alt', 'route' => route('settings.security')],
            ['id' => 'subscriptions', 'label' => 'Subscriptions', 'icon' => 'fas fa-layer-group', 'route' => route('settings.subscriptions')],
            ['id' => 'billing', 'label' => 'Billing', 'icon' => 'fas fa-credit-card', 'route' => route('settings.billing')],
        ];

        $plans = SubscriptionPlan::orderBy('price')->get();
        $topups = CreditsTopup::where('user_id', $user->id)->latest()->get();

        // Get active subscription (payment success and not expired)
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('payment_status', 'success')
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::today());
            })
            ->with('plan')
            ->orderBy('end_date', 'desc')
            ->first();

        $invoices = [
            [
                'date' => 'November 14th, 2025',
                'plan' => 'Top Up',
                'credits' => '500',
                'amount' => '₹590',
                'status' => 'pending',
                'invoice' => '#aeae9fd2-668c-459b-82a9-eabd2ed74ff9',
            ],
            [
                'date' => 'November 14th, 2025',
                'plan' => 'Top Up',
                'credits' => '500',
                'amount' => '₹590',
                'status' => 'pending',
                'invoice' => '#b4a21ed2-9b2a-4b2b-9cad-122ef9a7aa11',
            ],
            [
                'date' => 'November 12th, 2025',
                'plan' => 'Top Up',
                'credits' => '500',
                'amount' => '₹590',
                'status' => 'failed',
                'invoice' => '#8123dad2-9b2a-4b2b-9cad-122ef9a7ef77',
            ],
            [
                'date' => 'November 12th, 2025',
                'plan' => 'Free',
                'credits' => '100',
                'amount' => '₹0',
                'status' => 'success',
                'invoice' => '#9981ead2-9b2a-4b2b-9cad-122ef9a7cfd1',
            ],
        ];

        return compact(
            'user',
            'brandLogoUrl',
            'settingsMenu',
            'plans',
            'invoices',
            'firstName',
            'lastName',
            'initial',
            'activeTab',
            'topups',
            'activeSubscription'
        );
    }
}

