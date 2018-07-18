@extends('layouts.admin')


@section('main-content')
<h2 align="center"> Uploaded Images </h2>
<hr>
@if($images)
<div class="images-container">
  @foreach($images as $image)
  <div class="images-container-item hoverable">
  	<a href="/admin/images/{{$image->id}}">
  		<img src="/storage/images/{{$image->user->id}}/png/{{$image->path}}" alt="{{$image->path}}" height=100 width=150></img>
  	</a>
  </div>
  @endforeach
</div>
@endif
@endsection