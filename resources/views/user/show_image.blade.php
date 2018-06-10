@extends('layouts.user')

@section('main-content')
<div class="back-button"><i class="fa fa-chevron-left"></i></div>
<h2 align="center"> Image details  </h2>
<hr>
<div class="row">
<div class="col-md-6">
	<div class="images-container-item hoverable float-md-right mx-sm-auto">
   		<img id="myImg" src="/storage/images/{{Auth::user()->id}}/png/{{$image->path}}" height=100 width=150 alt="{{$image->path}}"></img>
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
		<li>Download {{strtoupper($image->extension)}}</li>
		@endif
		<li>Download PNG</li>
	</ul>
</div>
</div>
<div class="row">
<div class="col-md-8 offset-md-2">
	<table class="details-table table-bordered table-responsive">
		<tr>
			<td>Name:</td>
			<td class="break-word">{{$image->path}}</td>
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
</div>
<div class="row">
	<div class=" col-md-8 offset-md-2 text-right">
			                       @if ($errors->any())
                            <div class="alert alert-danger text-left" onclick="remove(this)">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif
		<form method="post" action="{{route('images.destroy', $image->id)}}">
			{{csrf_field()}}
			<input type="hidden" name="_method" value="delete">
			<button class="btn btn-danger delete-user-button" value="submit" onclick="buttonSubmit(this)">Delete image</button>
		</form>		
	</div>
</div>


@endsection