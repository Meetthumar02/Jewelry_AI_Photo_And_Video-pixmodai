<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {

                $user = Auth::user();

                /* ------------------------------
                 *  YOUR ORIGINAL CREDIT LOGIC
                 * ------------------------------ */

                $Total_credit = \App\Models\User\CreditTransaction::where('user_id', $user->id)
                    ->where('change_type', 'add')
                    ->sum('credits');

                $totalCredits = $user->total_credits;
                $maxCredits = $Total_credit;

                // Used Credits
                $usedCredits = $maxCredits - $totalCredits;

                // Progress %
                $progress = ($maxCredits > 0)
                    ? ($totalCredits / $maxCredits) * 100
                    : 0;

                /* -------------------------------------------
                 *   ★ DYNAMIC SIDEBAR COUNTS (NO MODEL)
                 * ------------------------------------------- */

                // Favorites table count
                $favoriteCount = DB::table('favorites')
                    ->where('user_id', $user->id)
                    ->count();

                // Catalog table count (your table is catalog_studios)
                $catalogueCount = DB::table('catalog_studios')
                    ->where('user_id', $user->id)
                    ->count();

                // Provide an alias variable so older Blade code ($catalogCount) still works
                $catalogCount = $catalogueCount;

                /* -------------------------------------------
                 *  SHARE TO ALL BLADE VIEWS
                 * ------------------------------------------- */

                $view->with([
                    // Original credit values
                    'globalCredits'       => $totalCredits,
                    'globalUsedCredits'   => $usedCredits,
                    'globalMaxCredits'    => $maxCredits,
                    'globalProgress'      => $progress,

                    // New dynamic counts (both names available)
                    'favoriteCount'       => $favoriteCount,
                    'catalogueCount'      => $catalogueCount,
                    'catalogCount'        => $catalogCount,
                ]);
            }
        });
    }
}
