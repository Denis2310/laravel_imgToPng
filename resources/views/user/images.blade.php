@extends('layouts.user')

@section('main-content')

<h2 align="center"> My Images </h2>
<hr>

	@if(Session::has('success'))
	<div class="row">
		<div class="col-md-4 alert alert-success ml-auto mr-auto text-center">
			{{Session::get('success')}}
		</div>
	</div>
	@endif

<div class="images-container">
@if($images)
@foreach($images as $image)
<div class="images-container-item hoverable">
	@if($image->to_user)
   		<a href="/received/{{$image->id}}"><img id="myImg" src="/storage/images/{{Auth::user()->id}}/received/{{$image->path}}" height=100 width=150 alt="{{$image->path}}"></img></a>
   		<div> received </div>
	@else
   		<a href="/images/{{$image->id}}"><img src="/storage/images/{{Auth::user()->id}}/png/{{$image->path}}" height=100 width=150></img></a>
   	@endif
</div>
@endforeach
@endif
</div>

@endsection