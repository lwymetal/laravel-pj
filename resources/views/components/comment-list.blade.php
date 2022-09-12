@forelse($comments as $comment)
  <p>
    {{ $comment->content }}
    <br>
    @component('components.tags', ['tags' => $comment->tags])
    @endcomponent
    @component('components.updated', [
      'created' => $comment->created_at, 
      'updated' => $comment->updated_at,
      'id' => $comment->user->id, 
      'name' => $comment->user->name
      ])
    @endcomponent
    <!-- <small class="text-muted">{{ $comment->created_at->diffForHumans() }} by {{ $comment->user->name }}</small> -->
  </p>
  @empty
  <p>{{ __('No comments yet') }}</p>
@endforelse