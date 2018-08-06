@extends('layouts.user')

@section('main-content')
<div class="back-button"><i class="fa fa-chevron-left"></i></div>
<h2 align="center"> Image details  </h2>
<hr>

<div class="row">
<div class="col-md-6">

	<div class="images-container-item hoverable float-md-right mx-sm-auto">
   		<img id="myImg" src="/storage/images/{{Auth::user()->id}}/received/{{$image->path}}" height=100 width=150 alt="{{$image->path}}"></img>
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
		@if($image->imageData->extension != "png")
		<li><a href="{{action('DownloadController@download_recv_image', [$image->id, true])}}">Download {{strtoupper($image->imageData->extension)}}</a></li>
		@endif
		<li><a href="{{action('DownloadController@download_recv_image', $image->id)}}">Download PNG</a></li>

	</ul>

</div>
</div> <!-- row closing tag -->

<div class="row">

<div class="col-md-8 offset-md-2">
	<table class="details-table table-bordered table-responsive">
		<tr>
			<td>Name:</td>
			<td class="break-word">{{substr($image->path, 10)}}</td>
		</tr>
		<tr>
			<td>Received from:</td>
			<td>{{$image->from_user}}</td>
		</tr>
		<tr>
			<td>Received type:</td>
			<td>PNG</td>
		</tr>
		<tr>
			<td>Original type:</td>
			<td>{{strtoupper($image->imageData->extension)}}</td>
		</tr>
		<tr>
			<td>PNG size:</td>
			<td>{{round($image->imageData->png_size/1024, 2)}} KB</td>
		</tr>
		<tr>
			<td>Received at:</td>
			<td>{{$image->created_at->format('H:m:s, d.M.Y')}}</td>
		</tr>
	</table>
</div>
</div> <!-- row closing tag -->

<div class="row">
	<div class=" col-md-8 offset-md-2 show-image-buttons text-right">

		<form method="post" action="{{route('received.destroy', $image->id)}}">
			{{csrf_field()}}
			<input type="hidden" name="_method" value="delete">
			<button class="btn btn-danger btn-margin-15" value="submit" onclick="buttonSubmit(this)">Delete image</button>
		</form>	
	
		@if ($errors->any())
        <div class="alert alert-danger text-left" onclick="remove(this)">
            <ul>
            @foreach ($errors->all() as $error)
              	<li>{{ $error }}</li>
            @endforeach
           	 </ul>
        </div>
        @endif
	</div> <!-- col-md-8 offset-md-2 -->
</div> <!-- row closing tag -->

@endsection