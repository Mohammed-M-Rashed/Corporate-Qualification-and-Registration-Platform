<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\QualificationRequest;
use App\Models\Committee;
use App\Policies\CompanyPolicy;
use App\Policies\QualificationRequestPolicy;
use App\Policies\CommitteePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        QualificationRequest::class => QualificationRequestPolicy::class,
        Committee::class => CommitteePolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
