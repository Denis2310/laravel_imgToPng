@extends('layouts.app')


@section('content')

    <div class="flex-container">
    <h1 class="text-center welcome-heading">Welcome to ImageApp</h1>

    	<div class="welcome">
    	<ul class="welcome-nav text-center">
    	@guest	
    		<li> <a href="{{route('login')}}"> Login </a> </li>
    		<li> <a href="{{route('register')}}"> Register </a> </li>
    	@else
    		<li> <a href="{{route('home')}}"> Home </a> </li>
    	@endguest
    	</ul>
    	<div>
    

	</div>

@endsection