@extends('layouts.user')

@section('main-content')
<h2 align="center">Received images</h2>
<hr>

	@if(Session::has('success'))
	<div class="row">
		<div class="col-md-4 alert alert-success ml-auto mr-auto text-center">
			{{Session::get('success')}}
		</div>
	</div>
	@endif

<div class="images-container">
@if(count($received_images) > 0)
@foreach($received_images as $image)
<div class="images-container-item hoverable">

   <a href="/received/{{$image->id}}"><img src="/storage/images/{{Auth::user()->id}}/received/{{$image->path}}" height=100 width=150></img></a>

</div>
@endforeach
@endif
</div>

@endsection