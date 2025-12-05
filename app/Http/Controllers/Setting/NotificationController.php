<?php

namespace App\Http\Controllers\Setting;

class NotificationController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('notifications'));
    }
}

