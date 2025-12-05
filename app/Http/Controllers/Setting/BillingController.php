<?php

namespace App\Http\Controllers\Setting;

class BillingController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('billing'));
    }
}

