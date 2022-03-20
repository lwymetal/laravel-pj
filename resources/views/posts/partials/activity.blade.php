<div class="container">

  <div class="row">
  @component('card',['title' => 'Most Commented'])
    @slot('subtitle')
      Very response, much discuss
    @endslot
    @slot('items')
      @foreach ($mostCommented as $mc)
        <li class="list-group-item">
          <a href="{{ route('posts.show', ['post' => $mc->id]) }}">
            {{ $mc->title }}
          </a>
        </li>
      @endforeach
    @endslot
  @endcomponent
  </div>

  <div class="row mt-4">
  @component('card',['title' => 'Most Active'])
    @slot('subtitle')
      They just won't shut up
    @endslot
    @slot('items', collect($mostActive)->pluck('name'))
  @endcomponent
  </div>

  <div class="row mt-4">
  @component('card',['title' => 'Recently Active'])
    @slot('subtitle')
      Within the last month
    @endslot
    @slot('items', collect($mostActiveLastMonth)->pluck('name'))
  @endcomponent
  </div>

</div>