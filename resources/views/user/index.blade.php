@extends('layouts.user')

@section('main-content')
<h2 align="center"> My Images </h2>
<hr>
<div class="images-container">
@if($images)

@foreach($images as $image)
<div class="images-container-item">
    <img src="images/{{Auth::user()->id}}/png/{{$image->path}}" height=100 width=150></img>
</div>
@endforeach

@endif
</div>

@endsection