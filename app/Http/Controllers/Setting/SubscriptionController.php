<?php

namespace App\Http\Controllers\Setting;

class SubscriptionController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('subscriptions'));
    }
}

