@extends('layouts.app')

@section('title', 'Contact page')

@section('content')
<h1>Contact</h1>

<p>Here is contact text</p>

@can('home.secret')
  <p>
    <a href="{{ route('secret') }}">
      Sekrit page
    </a>
  </p>
@endcan

@endsection