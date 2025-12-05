<?php

namespace App\Http\Controllers\Setting;

class AccountController extends BaseSettingController
{
    public function index()
    {
        return view('user.setting.index', $this->sharedData('account'));
    }
}

