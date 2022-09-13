<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}" defer></script>
  <title>Blog Project - @yield('title')</title>
</head>
<body>
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 border-bottom shadow-sm mb-3 bg-black text-white">
    <h5 class="my-0 mr-md-auto font-weight-normal">Blog Project</h5>
    <nav class="my-2 my-md-0 mr-md-3">
      <a href="{{ route('home.index') }}" class="p-2 text-white">{{ __('Home') }}</a>
      <a href="{{ route('home.contact') }}" class="p-2 text-white">{{ __('Contact') }}</a>
      <a href="{{ route('posts.index') }}" class="p-2 text-white">{{ __('Blog Posts') }}</a>
      <a href="{{ route('posts.create') }}" class="p-2 text-white">{{ __('Add') }}</a>
      @guest
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="p-2 text-white">{{ __('Register') }}</a>
        @endif
        <a href="{{ route('login') }}" class="p-2 text-white">{{ __('Log In') }}</a>
      @else
        <a href="{{ route('users.show', ['user' => Auth::user()->id]) }}" class="p-2 text-white">{{ __('View Profile') }}</a>
        <a href="{{ route('users.edit', ['user' => Auth::user()->id]) }}" class="p-2 text-white">{{ __('Edit Profile') }}</a>
        <a href="{{ route('logout') }}" class="p-2 text-white" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Log Out') }} {{ Auth::user()->name }}</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
          @csrf
        </form>
      @endguest
    </nav>
  </div>
  <div class="container">
    @if(session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif
    @yield('content')
  </div>
</body>
</html>