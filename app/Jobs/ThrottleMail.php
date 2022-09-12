<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Models\User;

class ThrottleMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 10;

    public $mail;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mailable $mail, User $user)
    {
      $this->mail = $mail;
      $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Redis::throttle('mailtrap')->allow(2)->every(12)->then(function () {
        Mail::to($this->user)->send($this->mail);
      }, function () {
        return $this->release(5); // retry in 5 seconds
      });
    }
}
