<?php

namespace App\Providers;

use App\Models\Api\ApiLink;
use App\Models\Link;
use App\Models\LinkList;
use App\Models\Note;
use App\Models\Tag;
use App\Policies\Api\ApiLinkPolicy;
use App\Policies\Api\LinkListApiPolicy;
use App\Policies\Api\NoteApiPolicy;
use App\Policies\Api\TagApiPolicy;
use App\Policies\ApiTokenPolicy;
use App\Policies\LinkListPolicy;
use App\Policies\LinkPolicy;
use App\Policies\NotePolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

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
        Note::class => NotePolicy::class,
        Tag::class => TagPolicy::class,
        PersonalAccessToken::class => ApiTokenPolicy::class,
        ApiLink::class => ApiLinkPolicy::class,
        LinkList::class . 'Api' => LinkListApiPolicy::class,
        Note::class . 'Api' => NoteApiPolicy::class,
        Tag::class . 'Api' => TagApiPolicy::class,
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
