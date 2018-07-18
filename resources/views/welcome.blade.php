@extends('layouts.app')


@section('content')

    <div class="flex-container">
    <h1 class="text-center welcome-heading">Aplikacija za pretvorbu i razmjenu slikovnih datoteka</h1>

    	<div class="welcome">
    	<ul class="welcome-nav text-center">
    	@guest	
    		<li> <a href="{{route('login')}}"> Login </a> </li>
    		<li> <a href="{{route('register')}}"> Register </a> </li>
    	@endguest
    	</ul>
    	<div>
    

	</div>

@endsection