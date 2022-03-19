@extends('layouts.app')

@section('title', $post->title)

@section('content')

<h1>{{ $post->title }} 
@component('badge', ['type' => 'success', 'show' => (now()->diffInMinutes($post->created_at) < 999)])
  New
@endcomponent
</h1>
<p>{{ $post->content }}</p>
<p>Published {{ $post->created_at->diffForHumans() }}
@if ($post->updated_at > $post->created_at)
(Updated {{ $post->updated_at->diffForHumans() }})
@endif
</p>

<h4>Comments</h4>

@forelse($post->comments as $comment)
<p>
  {{ $comment->content }}
  <br>
  <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
</p>
@empty
<p>No comments</p>
@endforelse

@endsection