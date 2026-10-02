<?php

namespace App\Providers;

use App\Contracts\Audits\CertificateInspector;
use App\Contracts\Audits\DnsResolver;
use App\Contracts\Audits\PageSpeedClient;
use App\Services\Audits\HttpPageSpeedClient;
use App\Services\Audits\StreamCertificateInspector;
use App\Services\Audits\SystemDnsResolver;
use App\Services\Audits\UrlSafety;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DnsResolver::class, SystemDnsResolver::class);
        $this->app->singleton(PageSpeedClient::class, HttpPageSpeedClient::class);
        $this->app->singleton(CertificateInspector::class, StreamCertificateInspector::class);
        $this->app->singleton(UrlSafety::class);
    }

    public function boot(): void
    {
        App::setLocale('fa');
    }
}
