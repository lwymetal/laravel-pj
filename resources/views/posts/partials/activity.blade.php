<div class="container">

  <div class="row">
  @component('card',['title' => __('Most Commented')])
    @slot('subtitle')
      {{ __('What people are currently talking about') }}
    @endslot
    @slot('items')
      @foreach ($mostCommented as $mc)
        <li class="list-group-item list-group-item-dark">
          <a class="link-dark" href="{{ route('posts.show', ['post' => $mc->id]) }}">
            {{ $mc->title }}
          </a>
        </li>
      @endforeach
    @endslot
  @endcomponent
  </div>

  <div class="row mt-4">
  @component('card',['title' => __('Most Active')])
    @slot('subtitle')
      {{ __('Writers with most posts written') }}
    @endslot
    @slot('items', collect($mostActive)->pluck('name'))
  @endcomponent
  </div>

  <div class="row mt-4">
  @component('card',['title' => __('Most Active Last Month')])
    @slot('subtitle')
      {{ __('Users with most posts last month') }}
    @endslot
    @slot('items', collect($mostActiveLastMonth)->pluck('name'))
  @endcomponent
  </div>

</div>