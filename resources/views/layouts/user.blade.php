@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left navigation bar -->
        <nav class="col-md-2 col-sm-4 nav-home">
            <ul>
                <li><a href="/images">My Images</a></li>
                <li><a href="/images/create">Upload Image</a></li>
                <li><a href="/received">Received</a></li>        
            </ul>
        </nav>

        <!-- main content home view -->
        <div class="col-md-10 col-sm-8 main-container">
            @yield('main-content')
        </div>

    </div>   
</div>
@endsection