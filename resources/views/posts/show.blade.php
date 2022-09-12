@extends('layouts.app')

@section('title', $post->title)

@section('content')

<div class="row">
  <div class="col-8">
    @if ($post->image)
      <div style="background-image: url('{{  $post->image->url() }}'); min-height: 500px; color: white; text-align: center; background-attachment: fixed; 
        background-repeat: no-repeat">
        <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
    @else
      <h1>
    @endif
      {{ $post->title }} 
    @component('components.badge', ['type' => 'success', 'show' => (now()->diffInMinutes($post->created_at) < 999)])
      New
    @endcomponent
    @if ($post->image)
        <!-- <img src="http://laravelpj:8888/storage/{{ $post->image->path }}" /> -->
        <!-- <img src="{{ asset($post->image->path) }}" /> doesn't work because of symbolic storage link -->
        <!-- <img src="{{ Storage::url($post->image->path) }}" /> -->
        <!-- <img src="{{ $post->image->url() }}" /> -->
        </h1>
      </div>
    @else
    </h1>
    @endif
    <p>{{ $post->content }}</p>

    @component('components.updated', [
      'created' => $post->created_at, 
      'updated' => $post->updated_at,
      'id' => $post->user->id, 
      'name' => $post->user->name
      ])
    @endcomponent

    @component('components.tags', ['tags' => $post->tags])
    @endcomponent

    <p>Current readers: {{ $counter }}</p>

    <p>(Translation test) {{ trans_choice('messages.people.reading', $counter) }} </p>

    <h4>{{ __('Comments') }}</h4>

    @component('components.comment-form', ['route' => route('posts.comments.store', ['post' => $post->id])])
    @endcomponent


    @component('components.comment-list', ['comments' => $post->comments])
    @endcomponent

  </div>
  <div class="col-4">
    @include('posts.partials.activity')
  </div>
</div>

@endsection