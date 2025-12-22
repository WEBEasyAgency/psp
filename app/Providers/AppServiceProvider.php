<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Blade::directive('assets', function ($entry) {
            return "<?php echo \\App\\Helpers\\AssetHelper::render({$entry}); ?>";
        });
    }
}
