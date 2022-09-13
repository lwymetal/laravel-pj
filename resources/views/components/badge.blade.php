@if (!isset($show) || $show)
<span class="badge badge-{{ $type ?? 'dark' }}">{{ $slot }}</span>
@endif