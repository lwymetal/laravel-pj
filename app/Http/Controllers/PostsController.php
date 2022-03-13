<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
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
        ['posts' => BlogPost::withCount('comments')->get()]
      );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
      $post->delete();

      session()->flash('status', 'Post deleted');

      return redirect()->route('posts.index');
    }
}