<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BlogPostCreated;
use App\Models\User;
use App\Jobs\ThrottleMail;
use App\Mail\BlogPostCreatedMail; // BlogPostCreated conflicts with event name

class NotifyAdminNewPost
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BlogPostCreated $event)
    {
      \Log::info('nanp 28');
      User::thatIsAdmin()->get()
        ->map(function (User $user) {
          ThrottleMail::dispatch(new BlogPostCreatedMail(), $user);
        });
    }
}
