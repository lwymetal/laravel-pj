<div class="mb-2 mt-2">
@auth
<form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST">
  @csrf
  <div class="form-group">
    <textarea class="form-control" name="content"></textarea>
  </div> 
  @component('components.errors')
  @endcomponent
  <button type="submit"class="btn btn-primary btn-block">Add comment</button>  
</form>
@else
<a href="{{ route('login') }}">Sign in</a> to add a comment
@endauth
</div>
<hr>