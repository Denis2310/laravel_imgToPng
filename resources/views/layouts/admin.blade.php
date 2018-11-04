@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- left navigation bar -->
        <nav class="col-md-2 col-sm-4 nav-home">
            <ul>
               <a href="/admin/users"> <li class="{{Request::is('admin/users')? 'active' : ''}}">Users</li> </a>
               <a href="/admin/images"> <li class="{{Request::is('admin/images')? 'active' : ''}}">Images</li> </a>
            </ul>
        </nav>

        <!-- main content home view -->
        <div class="col-md-10 offset-md-2 col-sm-8 offset-sm-4 main-container">
            @yield('main-content')
        </div>

    </div>   
</div>
@endsection