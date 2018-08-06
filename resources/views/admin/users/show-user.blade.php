@extends('layouts.admin')

@section('main-content')

<h2 class="text-center">User details: {{$user->name}}</h2>
<hr>
<div class="row">
	<div class="col-md-8 offset-md-2">
	<table class="details-table table-bordered table-responsive">
		<tr>
			<td>ID:</td>
			<td>{{$user->id}}</td>
		</tr>
		<tr>
			<td>Name:</td>
			<td>{{$user->name}}</td>
		</tr>
		<tr>
			<td>Email:</td>
			<td>{{$user->email}}</td>
		</tr>
		@if($user->role_id == 2)
		<tr>
			<td>Uploaded images:</td>
			<td>{{count($user->images)}}</td>
		</tr>
		<tr>
			<td>Received images:</td>
			<td>{{count($images) - count($user->images)}}</td>
		</tr>	
		@endif	
		<tr>
			<td>Registration date:</td>
			<td>{{$user->created_at->format('H:m:s, d.M.Y')}}</td>
		</tr>

		<tr>
			<td>Role:</td>
			<td>{{$user->role->name}}</td>
		</tr>
	</table>
	</div>
</div>
<div class="row">
	<div class=" col-md-8 offset-md-2 text-right">
			            @if ($errors->any())
                            <div class="alert alert-danger text-left" onclick="remove(this)">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif
		<form method="post" action="{{route('admin.users.destroy', $user->id)}}">
			{{csrf_field()}}
			<input type="hidden" name="_method" value="delete">
			<button class="btn btn-danger btn-margin-15" value="submit" onclick="buttonSubmit(this)">Delete user</button>
		</form>		
	</div>
</div>

<div class="row">
	<div class="col-md-12 show-user-images-row">
		<hr>
		<div class="images-container">
		@if($images)
			@foreach($images as $image)
				<div class="images-container-item hoverable">
					@if($image->to_user)
  					<a href="/admin/images/sent/{{$image->id}}">
   						<img src="/storage/images/{{$image->to_user}}/received/{{$image->path}}" height=100 width=150></img>

   					</a>
   					<div>received</div>
   					@else
  					<a href="/admin/images/{{$image->id}}">
   						<img src="/storage/images/{{$user->id}}/uploaded/{{$image->path}}" height=100 width=150></img>
   					</a>
   					@endif
				</div>
			@endforeach
		@endif
		</div>
	</div>
</div>

@endsection