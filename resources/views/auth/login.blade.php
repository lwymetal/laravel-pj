@extends('layouts.app')

@section('title', __('Log In'))

@section('content')
  <form method="POST" action="{{ route('login') }}"> 
    @csrf
    <div class="form-group">
      <label>{{ __('Email') }}</label>
      <input name="email" value="{{ old('email') }}" required class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"> 
      @if ($errors->has('email'))
      <span class="invalid-feedback">
        <strong>{{ $errors->first('email') }}</strong>
      <span>
      @endif
    </div>
    <div class="form-group">
      <label>{{ __('Password') }}</label>
      <input type="password" name="password" value="" required class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"> 
      @if ($errors->has('password'))
      <span class="invalid-feedback">
        <strong>{{ $errors->first('password') }}</strong>
      <span>
      @endif
    </div>
    <div class="form-group">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" value="{{ old('remember') ? 'checked' : '' }}">
        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">{{ __('Log In') }}</button>
  </form>
@endsection 