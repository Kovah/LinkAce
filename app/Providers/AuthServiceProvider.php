<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\LinkList;
use App\Policies\LinkListPolicy;
use App\Policies\LinkPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Link::class => LinkPolicy::class,
        LinkList::class => LinkListPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
