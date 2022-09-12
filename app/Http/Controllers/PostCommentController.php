<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreComment;
// use App\Mail\CommentPosted;
// use App\Mail\CommentPostedMarkdown;
// use App\Jobs\NotifyUsersPostWasCommented;
// use App\Jobs\ThrottleMail;
use App\Events\CommentPosted;
use App\Http\Resources\Comment as CommentResource; // avoid Comment model name conflict

class PostCommentController extends Controller
{

  public function __construct() {
    $this->middleware('auth')->only(['store']);
  }

  public function index(BlogPost $post) {
    // return $post->comments; // collection
    // return $post->comments(); // method
    // return new CommentResource($post->comments->first());
    return CommentResource::collection($post->comments()->with('user')->get());
    // return $post->comments()->with('user')->get();

  }

  public function store(BlogPost $post, StoreComment $request) { 
    // route binding: BlogPost $post takes post model from route, has user id
    $comment = $post->comments()->create(['user_id' => $request->user()->id, 'content' => $request->input('content')]);

    // Mail::to($post->user)->send(new CommentPosted($comment));
    // $request->session()->flash('status', 'Comment created');
    // return redirect()->back();

    // Mail::to($post->user)->send(new CommentPostedMarkdown($comment));

    // Mail::to($post->user)->queue(new CommentPostedMarkdown($comment));

    // $when = now()->addMinutes(1);

    // Mail::to($post->user)->later($when, new CommentPostedMarkdown($comment));


    event(new CommentPosted($comment));

    // moved to NotifyUsersAboutComment listener
    // ThrottleMail::dispatch(new CommentPostedMarkdown($comment), $post->user)
    //   ->onQueue('low');
    // NotifyUsersPostWasCommented::dispatch($comment)
    //   ->onQueue('high');

    return redirect()->back()->withStatus('Comment created');

  }
}
