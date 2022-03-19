<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{

  public function __construct() {
    $this->middleware('auth')
      ->only(['create', 'store', 'edit', 'update', 'destroy']);
  }
    /* php artisan make:controller PostsController --resource
    As a resource controller I am made with CRUD routes in place. */ 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // DB::connection()->enableQueryLog();
      // $posts = BlogPost::with('comments')->get();
      // foreach ($posts as $post) {
      //   foreach ($post->comments as $comment) {
      //     echo $comment->content;
      //   }
      // }
      // dd(DB::getQueryLog());
      return view(
        'posts.index', 
        ['posts' => BlogPost::latest()->withCount('comments')->get(),
          'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
          'mostActive' => User::withMostBlogPosts()->take(5)->get(),
          'mostActiveLastMonth' => User::withMostBlogPostsLastMonth()->take(5)->get()
        ]
      );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // $this->authorize('posts.create');
      return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
      $validated = $request->validated();
      $validated['user_id'] = $request->user()->id;
      $post = BlogPost::create($validated);

      $request->session()->flash('status', 'Post created');

      return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      // abort_if(!isset($this->posts[$id]), 404);
      return view('posts.show', ['post' => BlogPost::with('comments')->findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $post = BlogPost::findOrFail($id);
      // if (Gate::denies('update-post', $post)) {
      //   abort(403, 'Nacho post! [Edit]');
      // };
      $this->authorize($post);
      return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
      $post = BlogPost::findOrFail($id);
      // if (Gate::denies('update-post', $post)) {
      //   abort(403, 'Nacho post!');
      // };
      $this->authorize($post);
      $validated = $request->validated();
      $post->fill($validated)->save();
      $request->session()->flash('status', 'Post updated');
      return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $post = BlogPost::findOrFail($id);
      $this->authorize($post);
      // if (Gate::denies('delete-post', $post)) {
      //   abort(403, 'Nacho post! [Delete]');
      // };
      $post->delete();

      session()->flash('status', 'Post deleted');

      return redirect()->route('posts.index');
    }
}