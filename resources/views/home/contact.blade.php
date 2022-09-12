@extends('layouts.app')

@section('title', __('Contact'))

@section('content')
<h1>{{ __('Contact') }}</h1>

<p>{{ __('Hello this is contact!') }}</p>

@can('home.secret')
  <p>
    <a href="{{ route('secret') }}">
      Sekrit page
    </a>
  </p>
@endcan

@endsection