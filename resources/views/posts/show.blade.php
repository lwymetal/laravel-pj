@extends('layouts.app')

@section('title', $post->title)

@section('content')

<div class="row">
  <div class="col-8">

    <h1>{{ $post->title }} 
    @component('components.badge', ['type' => 'success', 'show' => (now()->diffInMinutes($post->created_at) < 999)])
      New
    @endcomponent
    </h1>
    <p>{{ $post->content }}</p>
    <p>Published {{ $post->created_at->diffForHumans() }} by {{ $post->user->name }}
    @if ($post->updated_at > $post->created_at)
    (Updated {{ $post->updated_at->diffForHumans() }})
    @endif
    </p>

    @component('components.tags', ['tags' => $post->tags])
    @endcomponent

    <p>Current readers: {{ $counter }}</p>

    <h4>Comments</h4>

    @include('comments.form')

    @forelse($post->comments as $comment)
    <p>
      {{ $comment->content }}
      <br>
      <small class="text-muted">{{ $comment->created_at->diffForHumans() }} by {{ $comment->user->name }}</small>
    </p>
    @empty
    <p>No comments</p>
    @endforelse
  </div>
  <div class="col-4">
    @include('posts.partials.activity')
  </div>
</div>

@endsection