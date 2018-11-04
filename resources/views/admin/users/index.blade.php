@extends('layouts.admin')


@section('main-content')
<h2 align="center"> Registered users </h2>
<hr>
  @if(Session::has('success'))
  <div class="row">
    <div class="col-md-4 alert alert-success ml-auto mr-auto text-center"> 
      {{Session::get('success')}}
    </div>
  </div>
  @endif
<div class="row col-md-12 table-responsive">

<table class="users-table">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Registered at</th>
    <th>Role</th>
  </tr>
  @if(count($users) > 0)
  @foreach($users as $user)
    <tr class="clickable" data-url="{{route('admin.users.show', $user->id)}}">
      <td>{{$user->id}}</td>
      <td>{{$user->name}}</td>
      <td>{{$user->email}}</td>
      <td>{{$user->created_at->format('H:m:s, d.M.Y')}}</td>
      <td class="td_user_role">{{$user->role->name}}</td>
    </tr>
  @endforeach
  @endif
</table>
</div>
<div class="row col-md-12">
  <a class="button-link-style ml-auto btn-margin-15 btn btn-primary" href="/admin/users/create"> Add User </a>
    <!--<button class="btn btn-primary ml-auto btn-margin-15"></button>-->
</div>
@endsection