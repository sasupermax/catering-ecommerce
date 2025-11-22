<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;

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
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            
            // Forzar el flag Secure en cookies de sesión
            $this->app['config']->set('session.secure', true);
        }

        // Directiva Blade para agregar nonce a scripts
        Blade::directive('cspNonce', function () {
            return "<?php echo 'nonce=\"' . csp_nonce() . '\"'; ?>";
        });
    }
}
