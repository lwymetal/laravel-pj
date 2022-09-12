@extends('layouts.app')



@section('title', __('Create new post'))

@section('content')
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @include('posts.partials.form')
  <div><input type="submit" value="Create" class="btn btn-primary btn-block"></div>  
</form>
@endsection