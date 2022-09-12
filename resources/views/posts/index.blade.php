@extends('layouts.app')

@section('title', __('Blog Posts'))

@section('content')

  {{-- @each('posts.partials.post', $posts, 'post') --}}

<div class="row">
  <div class="col-8">
  @forelse($posts as $key => $post)
    @include('posts.partials.post')
  @empty
    <div>{{ __('No blog posts yet') }}</div>
  @endforelse
  </div>
  <div class="col-4">
    @include('posts.partials.activity')
  </div>
</div>
@endsection