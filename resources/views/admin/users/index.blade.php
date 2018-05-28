@extends('layouts.admin')


@section('main-content')
<h2 align="center"> Registered users </h2>
<div>
@if($users)
<table class="users-table">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Uploaded Pictures</th>
    <th>Registered at</th>
    <th>Role</th>
  </tr>
  @foreach($users as $user)
    <tr data-url="{{route('users.edit', $user->id)}}">
      <td>{{$user->id}}</td>
      <td>{{$user->name}}</td>
      <td>{{$user->email}}</td>
      <td>{{count($user->images)}}</td>
      <td>{{$user->created_at->format('H:m:s, d.M.Y')}}</td>
      <td>{{$user->role->name}}</td>
    </tr>
  @endforeach
</table>
@endif
  <div class="button-container">
    <button class="btn btn-primary"><a class="button-link-style" href="/admin/users/create"> Add User </a></button>
  </div>
</div>
@endsection