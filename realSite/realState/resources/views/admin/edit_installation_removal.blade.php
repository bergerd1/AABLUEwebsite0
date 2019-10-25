@extends('admin.layouts.backend_layout')
<!--header nav-->
 

@section('content') 
<div class="container-fluid">
    <div class="row col-md-offset-3 col-md-6">

        <br>

        
        
        
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
    <div class="col-md-9 col-md-offset-1">
        <h1>Edit Installation\Removal Cost </h1>
    </div>


    <div class="col-md-9 col-md-offset-1 panel panel-default">

                    <div class="panel-body">
            <form action="{{url('update_value')}}" enctype="multipart/form-data" method="POST">
               
                {{csrf_field()}}
@foreach($cost_value as  $value) 
                   <div>
                     <div class="form-group ">
                       <label for="description-field">Title</label>
                         <input type="text" name="title" class="form-control" value="{{$value->title}}">
                                           </div>

                    <!--<div class="form-group ">-->
                    <!--   <label for="description-field">Description</label>-->
                    <!--    <input type="text" class="form-control" value="{{$value->description}}">-->
                    <!--                       </div>-->
                    <div class="form-group ">
                       <label for="description-field">Price</label>
                        $<input type="text" name="price" value="{{$value->price}}" class="form-control">
                                           </div>
  <input type="hidden" name="Type" value="{{$value->Type}}">  
  <input type="hidden" name="id" value="{{$value->id}}">
                    @endforeach 

                <a class="btn btn-default" href="">Back</a>
                <button class="btn btn-default" type="submit">Save</button>
                </div>
            </form>
           </div>

        </div>
    </div>


</div>
        </div>
    </div><!-- /.container -->
</div>
</div>



@endsection