@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left navigation bar -->
        <nav class="col-md-2 col-sm-4 nav-home">
            <ul>
               <a href="/images"> <li class="{{Request::is('images')? 'active' : ''}}">My Images</li> </a>
               <a href="/images/create"> <li class="{{Request::is('images/create')? 'active' : ''}}">Upload Image</li> </a>
               <a href="/received"> <li class="{{Request::is('received')? 'active' : ''}}">Received</li>  </a>      
            </ul>
        </nav>

        <!-- main content home view -->
        <div class="col-md-10 offset-md-2 col-sm-8 offset-sm-4 main-container">
            @yield('main-content')
        </div>

    </div>   
</div>
@endsection