<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\NotifyUsersAboutComment;
use App\Listeners\NotifyAdminNewPost;
use App\Events\CommentPosted;
use App\Events\BlogPostCreated;
use App\Listeners\CacheSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentPosted::class => [
          NotifyUsersAboutComment::class
        ],
        BlogPostCreated::class => [
          NotifyAdminNewPost::class
        ]
    ];

    protected $subscribe = [
      CacheSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
