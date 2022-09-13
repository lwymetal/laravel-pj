<div class="mb-2 mt-2">
@auth
<form class="form-clear" action="{{ $route }}" method="POST">
  @csrf
  <div class="form-group">
    <textarea class="form-control" name="content"></textarea>
  </div> 
  @component('components.errors')
  @endcomponent
  <button type="submit"class="btn btn-primary btn-block">{{ __('Add comment') }}</button>  
</form>
@else
<a href="{{ route('login') }}">{{ __('Sign in') }}</a> {{ __('to post comments') }}
@endauth
</div>
<hr>