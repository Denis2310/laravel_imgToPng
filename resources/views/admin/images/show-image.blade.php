@extends('layouts.admin')

@section('main-content')

<h2 align="center"> Image details  </h2>
<hr>
<div class="row">
<div class="col-md-6">
	<div class="images-container-item hoverable float-md-right mx-sm-auto">
   		<img src="/storage/images/{{$image->user_id}}/uploaded/{{$image->path}}" height=100 width=150></img>
	</div>

	<!--Div za prikazivanje velike slike-->
   	<div id="myModal" class="modal">
   		<span class="close">&times;</span>
   		<img class="modal-content" id="img01">
   		<div id="caption"></div>
   	</div>

</div>
	
<div class="col-md-6 image-download-container">
	<ul>
		@if($image->extension != "png")
		<li><a href="{{action('DownloadController@download_user_image', [$image->id, true])}}">Download {{strtoupper($image->extension)}}</a></li>
		@endif
		<li><a href="{{action('DownloadController@download_user_image', $image->id)}}">Download PNG</a></li>
	</ul>
</div>
</div>
<div class="row">
<div class="col-md-8 offset-md-2">
	<table class="details-table table-bordered table-responsive">
		<tr>
			<td>Name:</td>
			<td>{{substr($image->path, 10)}}</td>
		</tr>
		<tr>
			<td>Owner:</td>
			<td>{{$image->user->name}}</td>
		</tr>
		<tr>
			<td>Upload type:</td>
			<td>{{strtoupper($image->extension)}}</td>
		</tr>
		@if($image->extension != "png")
		<tr>
			<td>Upload size:</td>
			<td>{{round($image->size/1024, 2)}} KB</td>
		</tr>
		@endif
		<tr>
			<td>PNG_size:</td>
			<td>{{round($image->png_size/1024, 2)}} KB</td>
		</tr>
		<tr>
			<td>Uploaded_at:</td>
			<td>{{$image->created_at->format('H:m:s, d.M.Y')}}</td>
		</tr>
	</table>
</div>
</div>
<div class="row">
	<div class=" col-md-8 offset-md-2">
		@if ($errors->any())
        	<div class="alert alert-danger text-left" onclick="remove(this)">
            	<ul>
            	@foreach ($errors->all() as $error)
              		<li>{{ $error }}</li>
            	@endforeach
           	 	</ul>
        	</div>
        @endif
        <div class="row">
        	<div class="col-md-12 show-image-buttons text-right">
				<form method="post" action="{{route('admin.images.destroy', $image->id)}}">
					{{csrf_field()}}
					<input type="hidden" name="_method" value="delete">
					<button class="btn btn-danger btn-margin-15" value="submit" onclick="buttonSubmit(this)">Delete image</button>
				</form>	
			</div>

		</div>	<!-- row closing tag -->


@endsection