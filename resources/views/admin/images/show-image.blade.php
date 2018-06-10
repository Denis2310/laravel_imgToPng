@extends('layouts.admin')

@section('main-content')

<h2 align="center"> Image details  </h2>
<hr>
<div class="row">
<div class="col-md-6">
	<div class="images-container-item centered ml-auto">
   		<img src="/storage/images/{{$image->user_id}}/png/{{$image->path}}" height=100 width=150></img>
	</div>
</div>
	
<div class="col-md-6 image-download-container">
	<ul>
		@if($image->extension != "png")
		<li>Download {{$image->extension}}</li>
		@endif
		<li>Download PNG</li>
	</ul>
</div>
</div>
<div class="row col-md-8 offset-md-2">
	<table class="details-table table-bordered table-responsive">
		<tr>
			<td>Name:</td>
			<td>{{$image->path}}</td>
		</tr>
		<tr>
			<td>Owner:</td>
			<td>{{$image->user->name}}</td>
		</tr>
		<tr>
			<td>Upload type:</td>
			<td>{{$image->extension}}</td>
		</tr>
		@if($image->extension != "png")
		<tr>
			<td>Upload size:</td>
			<td>{{$image->size/1000}} KB</td>
		</tr>
		@endif
		<tr>
			<td>PNG_size:</td>
			<td>{{$image->png_size/1000}} KB</td>
		</tr>
		<tr>
			<td>Uploaded_at:</td>
			<td>{{$image->created_at->format('H:m:s, d.M.Y')}}</td>
		</tr>
	</table>
</div>
<div class="row">

</div>


@endsection