@extends('layouts.user')

@section('main-content')
<h2 align="center">Upload Image</h2>
<hr>
<!--images upload form -->
<div class="row justify-content-center">
        <div class="col-md-6 col-sm-10 upload-container">
            <div class="card">
                <div class="card-header">Upload Image</div>

                <div class="card-body form-group">

                    <form method="post" action="{{action('UserImagesController@store')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <label class="btn btn-secondary ml-auto" for="inputImage">Select Image</label>
                	   <input type="file" class="form-control-file" id="inputImage" name="file"></input>
                       <p class="selected-file-name"></p>
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
                	   <button class="btn btn-primary" value="submit" onclick="buttonSubmit(this)"> Upload </button>             
                	   </div>
                       <div class="upload-file-types-text">Only PNG, BMP, JPG and GIF file types are allowed</div>
                    </form>

                </div>

            </div>
        </div>
</div>


@endsection