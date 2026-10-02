<?php

namespace App\Providers;

use App\Contracts\Audits\DnsResolver;
use App\Services\Audits\SystemDnsResolver;
use App\Services\Audits\UrlSafety;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DnsResolver::class, SystemDnsResolver::class);
        $this->app->singleton(UrlSafety::class);
    }

    public function boot(): void
    {
        App::setLocale('fa');
    }
}
