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
<p class="text-muted">
  {{ $post->updated_at > $post->created_at ? 'Updated' : 'Added'}} {{ $post->created_at->diffForHumans() }} by {{ $post->user->name }}
</p>
@if ($post->comments_count) 
  <p>{{ $post->comments_count }} comments</p>
@else 
  <p>No comments</p>
@endif
<div class="mb-3">
  @cannot('update', $post)
  <input type="submit" class="btn btn-primary" value="Edit" disabled>
  @endcannot
  @can('update', $post)
  <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
  @endcan
  @cannot('delete', $post)
  <input type="submit" value="Delete" class="btn btn-primary" disabled>
  @endcannot
  @if (!$post->trashed())
    @can('delete', $post)
    <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <input type="submit" value="Delete" class="btn btn-primary">
    </form>
    @endcan
  @endif
</div>  