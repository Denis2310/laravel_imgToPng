@extends('layouts.admin')

@section('main-content')

<h2 align="center">Add new User</h2>
<hr>

<!--add new user form -->
<div class="row justify-content-center">
        <div class="col-md-8 col-sm-10 upload-container">
            <div class="card">
                <div class="card-header">Add new user</div>

                <div class="card-body form-group">

                    <form method="post" action="{{action('UserImagesController@store'}}" enctype="multipart/form-data">
                        @csrf

                        <label for="username">Name:</label>
                	   	<input id="username" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif

                	   	<label for="email">Email:</label>
                	   	<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                 	   	<label for="password">Password:</label>
                	   	<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>   

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                 	   	<label for="password-confirm">Confirm password:</label>
                	   	<input id="password-confirm" type="password" class="form-control" name="password-confirmation">            	   	
                       @if ($errors->any())
                            <div class="alert alert-danger" onclick="remove(this)">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif
                   
                	   <div class="form-button-wrapper">
                	   		<button class="btn btn-primary" value="submit" onclick="buttonSubmit(this)"> Register user </button>                   
                	   </div>
                    </form>

                </div>

            </div>
        </div>
</div>
@endsection