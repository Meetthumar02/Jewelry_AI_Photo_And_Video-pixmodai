<?php

namespace App\Http\Controllers\Setting;

class SecurityController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('security'));
    }
}

