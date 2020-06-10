<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Post' => 'App\Policies\PostPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Friend' => 'App\Policies\FriendPolicy',
        'App\FriendRequest' => 'App\Policies\FriendRequestPolicy',
        'App\GroupOfFriends' => 'App\Policies\GroupPolicy',
        'App\GroupMember' => 'App\Policies\GroupMemberPolicy',
        'App\ContentReport' => 'App\Policies\ReportPolicy',
        'App\Message' => 'App\Policies\MessagePolicy',
        'App\Notification' => 'App\Policies\NotificationPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
