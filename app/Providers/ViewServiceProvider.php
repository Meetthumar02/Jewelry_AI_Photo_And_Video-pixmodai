<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Favorite;
use App\Models\Catalog;   // your catalog model

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {

            $favoriteCount = Favorite::where('user_id', auth()->id())->count();

            $catalogCount = Catalog::where('user_id', auth()->id())->count();

            $view->with([
                'favoriteCount' => $favoriteCount ?? 0,
                'catalogCount'  => $catalogCount ?? 0,
            ]);
        });
    }
}
