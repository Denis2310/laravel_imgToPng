@extends('layouts.user')

@section('main-content')

<h2 align="center"> Welcome, {{Auth::user()->name}} </h2>
<hr>

<div class="row col-md-10 offset-md-1">
		<ul class="home-nav">
			<li><a href="/images">Images</a></li>
			<li><a href="/upload">Upload</a></li>
			<li><a href="/received">Received</a></li>
		</ul>
</div>

@endsection