{{-- @break($key == 1) --}} <!-- stop -->
{{-- @continue($key == 1) --}} <!-- skip this -->
<h3>
  @if($post->trashed())
  <del>
  @endif
  <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
  @if($post->trashed()) 
  </del>
  @endif
</h3>

@component('components.updated', [
  'created' => $post->created_at, 
  'updated' => $post->updated_at,
  'id' => $post->user->id, 
  'name' => $post->user->name
  ])
@endcomponent

@component('components.tags', ['tags' => $post->tags])
@endcomponent


<p>
{{ trans_choice('messages.comments', $post->comments_count) }}
</p>

<div class="mb-4">
  @cannot('update', $post)
  <input type="submit" class="btn btn-primary" value="Edit" disabled>
  @endcannot
  @can('update', $post)
  <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
  @endcan
  @cannot('delete', $post)
  <input type="submit" value="Delete" class="btn btn-primary" disabled>
  @endcannot
  @auth
    @if (!$post->trashed())
      @can('delete', $post)
      <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
      @csrf
      @method('DELETE')
      <input type="submit" value="Delete" class="btn btn-primary">
      </form>
      @endcan
    @endif
  @endauth
</div>  