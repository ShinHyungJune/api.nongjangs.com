<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
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
        $this->bootKakaoCustomSocialite();

        $this->bootNaverCustomSocialite();
    }

    private function bootKakaoCustomSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'kakaoCustom',
            function ($app) use ($socialite) {
                $config = $app['config']['services.kakaoCustom'];
                return $socialite->buildProvider(KakaoCustomProvider::class, $config);
            }
        );
    }

    private function bootNaverCustomSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'naverCustom',
            function ($app) use ($socialite) {
                $config = $app['config']['services.naverCustom'];
                return $socialite->buildProvider(NaverCustomProvider::class, $config);
            }
        );
    }
}
