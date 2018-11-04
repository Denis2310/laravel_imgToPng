@extends('layouts.admin')


@section('main-content')
<h2 align="center"> Uploaded Images </h2>
<hr>

@if(Session::has('success'))
 <div class="row">
   <div class="col-md-4 alert alert-success ml-auto mr-auto text-center"> 
     {{Session::get('success')}}
   </div>
 </div>
@endif

@if(count($images) > 0)
<div class="images-container">
  @foreach($images as $image)
  <div class="images-container-item hoverable">
  @if($image->to_user)
    <a href="/admin/images/sent/{{$image->id}}">
      <img src="/storage/images/{{$image->to_user}}/received/{{$image->path}}" alt="{{$image->path}}" height=100 width=150></img>
    </a>
    <div> sent </div>
  @else
    <a href="/admin/images/{{$image->id}}">
      <img src="/storage/images/{{$image->user->id}}/uploaded/{{$image->path}}" alt="{{$image->path}}" height=100 width=150></img>
    </a>
  @endif
  </div>
  @endforeach
</div>
@endif
@endsection