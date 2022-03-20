<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Redis;
use Barryvdh\Debugbar\Facades\Debugbar;

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
      Debugbar::info('posts controller index');

      // $posts = Cache::remember('posts', now()->addSeconds(10), function() {
      //   return BlogPost::latest()->withCount('comments')->with('user')->get();
      // });

      // $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', now()->addSeconds(10), function() {
      //   return BlogPost::mostCommented()->take(5)->get();
      // });
      // $mostActive = Cache::remember('users-most-active', now()->addSeconds(10), function() {
      //   return User::withMostBlogPosts()->take(5)->get();
      // });
      // $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function() {
      //   return User::withMostBlogPostsLastMonth()->take(5)->get();
      // });

      return view(
        'posts.index', 
        ['posts' => BlogPost::latest()->withCount('comments')->with('user')->with('tags')->get(),
          // 'mostCommented' => $mostCommented,  // replaced by ViewComposers/ActivityComposer
          // 'mostActive' => $mostActive,
          // 'mostActiveLastMonth' => $mostActiveLastMonth
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
      // $blogPost = Cache::remember('blog-post-{$id}', 60, function() use ($id) {
      //   return BlogPost::with('comments')->findOrFail($id);
      // });
      $blogPost = Cache::tags(['blog-post'])->remember('blog-post-{$id}', 60, function() use ($id) {
        return BlogPost::with('comments')->with('tags')->with('user')->findOrFail($id);
      });

      $sessionId = session()->getId();
      $counterKey = 'blog-post-{$id}-counter';
      $usersKey = 'blog-post-{$id}-users';

      $users = Cache::tags(['blog-post'])->get($usersKey, []);
      $usersUpdate = [];
      $diff = 0;
      $now = now();

      foreach ($users as $session => $lastVisit) {
        if ($now->diffInMinutes($lastVisit) >= 1) {
          $diff--;
        } else {
          $usersUpdate[$session] = $lastVisit;
        }
      }

      if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= 1) {
        $diff++;
      }

      $usersUpdate[$sessionId] = $now;
      Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
      if (!Cache::tags(['blog-post'])->has($counterKey)) {
        Cache::tags(['blog-post'])->forever($counterKey, 1); // count first user 
      } else {
        Cache::tags(['blog-post'])->increment($counterKey, $diff); // diff is 1 only if user is new/refreshed
      // cache increment can be negative
      }

      $counter = Cache::tags(['blog-post'])->get($counterKey);

      return view('posts.show', ['post' => $blogPost, 'counter' => $counter]);
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