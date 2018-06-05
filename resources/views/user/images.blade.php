@extends('layouts.user')

@section('main-content')

<h2 align="center"> My Images </h2>
<hr>
<div class="images-container"> <!--images-container-->
@if($images)

@foreach($images as $image)
<div class="images-container-item"> <!--images-container-item-->

   <a href="/images/{{$image->id}}"><img src="user_images/{{Auth::user()->id}}/png/{{$image->path}}" height=100 width=150></img></a>

</div>
@endforeach

@endif
</div>

@endsection