@extends('layouts.app')

@section('title', 'Index')

@section('content')
<h1>{{ __('messages.welcome') }}</h1>
<h1>@lang('messages.welcome')</h1>

<h2>Hello World</h2>

<p>{{ __('messages.example_with_value', ['name' => 'User']) }}</p>

<p>{{ trans_choice('messages.plural', 0, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 1, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 2, ['a' => 1]) }}</p>

<p>Using JSON: {{ __('Welcome to Laravel!') }}</p>
<p>JSON with variable: {{ __('Hello :name', ['name' => 'User']) }}</p>

@endsection