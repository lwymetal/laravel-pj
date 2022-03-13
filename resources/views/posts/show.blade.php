@extends('layouts.app')

@section('title', $post->title)

@section('content')

<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>
<p>Published {{ $post->created_at->diffForHumans() }}</p>

@if(now()->diffInMinutes($post->created_at) < 5)
<div class="alert alert-info">New</div>
@endif

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