@extends('layouts.app')

@section('title', 'All Posts')

@section('content')

  {{-- @each('posts.partials.post', $posts, 'post') --}}

  @forelse($posts as $key => $post)
    @include('posts.partials.post')
  @empty
    <div>No posts found.</div>
  @endforelse


@endsection