<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;

class UserCommentController extends Controller
{
  public function __construct() {
    $this->middleware('auth')->only(['store']);
  }

  public function store(User $user, StoreComment $request) { 
    // dd($request->user()->id);
    $user->commentsOn()->create(['user_id' => $request->user()->id, 'content' => $request->input('content')]);

    // $request->session()->flash('status', 'Comment created');
    // return redirect()->back()
    return redirect()->back()->withStatus('Comment created');

  }
}
