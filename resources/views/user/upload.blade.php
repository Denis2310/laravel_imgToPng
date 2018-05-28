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

                    <form method="post" action="{{action('ImageController@store')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <label class="btn btn-default" for="inputImage"><span class="glyphicon glyphicon-file">Select image</span></label>
                	   <input type="file" class="form-control-file" id="inputImage" name="image"></input>

                       @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                	   <div class="form-button-wrapper">

                	   <button class="btn btn-primary"> Upload </button>
                	   <!--<button class="btn btn-default"><a href="/home"> Back </a></button>-->
                        
                	   </div>
                    </form>

                </div>

            </div>
        </div>
</div>


@endsection