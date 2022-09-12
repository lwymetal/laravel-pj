<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;

class CommentPostedMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
      $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      // $post = new BlogPost(); // to induce fail 
      
        $subject = "A comment was posted on {$this->comment->commentable->title}";
        return $this->subject($subject)
          ->markdown('emails.posts.commented-markdown');
    }
}
