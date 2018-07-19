@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left navigation bar -->
        <nav class="col-md-2 col-sm-4 nav-home">
            <ul>
                <li><a href="/admin/users">Users</a></li>
                <li><a href="/admin/images">Images</a></li>
            </ul>
        </nav>

        <!-- main content home view -->
        <div class="col-md-10 offset-md-2 col-sm-8 offset-sm-4 main-container">
            @yield('main-content')
        </div>

    </div>   
</div>
@endsection