<p class="text-muted">
  {{ __('Added') }} {{ $created->diffForHumans() }} 
  {{ __('by') }}
  @if (isset($id))
  <a href="{{ route('users.show', ['user' => $id]) }}">{{ $name }}</a>
  @else
  {{ $name }}
  @endif

  @if ($updated > $created)
  ({{ __('Updated') }} {{ $updated->diffForHumans() }})
  @endif
</p>