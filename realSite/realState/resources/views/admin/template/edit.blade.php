@extends('admin.layouts.backend_layout')
<!--header nav-->
 

@section('content')

 <style>
 
.border
{	
    
    border:1px solid #ccc;	padding:50px;
    
}
 
 </style>

<div class="col-sm-8 col-sm-offset-2  border">
    <h2>Template Creator</h2>
      <form method="POST" action="{{url('/templates/'.$item->id)}}" enctype="multipart/form-data">
          {{csrf_field()}}
          {{method_field('PUT')}}
        <div class="col-sm-8">
          <div class="form-group">
              <label>Name Of Template</label>
                <input type="text" name="temp_name" value="{{ $item->temp_name }}" class="form-control">
            </div>
        <div class="form-group">
            <label>Description Of Template</label>
            <input type="text" name="temp_description" value="{{ $item->temp_description }}" class="form-control">
        </div>
                <div class="form-group">
            <label>Price</label>
            <input type="text" name="temp_price" value="{{ $item->temp_price }}" class="form-control">
        </div>
      </div>
    <div class="col-sm-4">
      <img src="../../backend/template_picture/{{ $item->temp_picture }}" width="200px" height="200px"/>   
      <div class="form-group">
            <label>upload</label>
            <input type="file" name="temp_img" class="form-control" >
            <input type="hidden" name="temp_img_data" value="{{ $item->temp_picture}}">
        </div>

    </div>

            <a href="{{url('templates')}}" class="btn btn-default">  Back </a>
            <button type="submit" class="btn btn-default pull-right">Continue</button>
        
        </form>
    
     </div>











@endsection