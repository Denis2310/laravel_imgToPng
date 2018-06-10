@extends('layouts.admin')


@section('main-content')
<h2 align="center"> Registered users </h2>
<hr>
<div class="row col-md-12 table-responsive">

<table class="users-table">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Registered at</th>
    <th>Role</th>
  </tr>
  @if($users)
  @foreach($users as $user)
    <tr class="clickable" data-url="{{route('users.show', $user->id)}}">
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
    <button class="btn btn-primary ml-auto add-user-button"><a class="button-link-style" href="/admin/users/create"> Add User </a></button>
</div>
@endsection