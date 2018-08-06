@extends('layouts.admin')

@section('main-content')
<div class="back-button"><i class="fa fa-chevron-left"></i></div>
<h2 align="center"> Image details  </h2>
<hr>

<div class="row">
<div class="col-md-6">

	<div class="images-container-item hoverable float-md-right mx-sm-auto">
   		<img id="myImg" src="/storage/images/{{$image->to_user}}/received/{{$image->path}}" height=100 width=150 alt="{{$image->path}}"></img>
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
		@if($image_data->extension != "png")
		<li><a href="{{action('DownloadController@download_recv_image', [$image->id, true])}}">Download {{strtoupper($image_data->extension)}}</a></li>
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
			<td>From user:</td>
			<td>{{$image->from_user}}</td>
		</tr>
		<tr>
			<td>To user:</td>
			<td>{{$to_user}}</td>
		</tr>
		<tr>
			<td>Sent type:</td>
			<td>PNG</td>
		</tr>
		<tr>
			<td>Original type:</td>
			<td>{{strtoupper($image_data->extension)}}</td>
		</tr>
		@if($image_data->extension != 'png')
		<tr>
			<td>{{strtoupper($image_data->extension)}} size:</td>
			<td>{{round($image_data->size/1024, 2)}} KB</td>
		</tr>
		@endif
		<tr>
			<td>PNG size:</td>
			<td>{{round($image_data->png_size/1024, 2)}} KB</td>
		</tr>
		<tr>
			<td>Sent at:</td>
			<td>{{$image->created_at->format('H:m:s, d.M.Y')}}</td>
		</tr>
	</table>
</div>
</div> <!-- row closing tag -->

<div class="row">
	<div class=" col-md-8 offset-md-2 show-image-buttons text-right">
		@if ($errors->any())
        	<div class="alert alert-danger text-left" onclick="remove(this)">
            	<ul>
            	@foreach ($errors->all() as $error)
              		<li>{{ $error }}</li>
            	@endforeach
           	 	</ul>
        	</div>
        @endif
		<form method="post" action="{{route('received.destroy', $image->id)}}">
			{{csrf_field()}}
			<input type="hidden" name="_method" value="delete">
			<button class="btn btn-danger btn-margin-15" value="submit" onclick="buttonSubmit(this)">Delete image</button>
		</form>	
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