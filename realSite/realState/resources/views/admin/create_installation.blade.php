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
        <h1>Create Installation Cost </h1>
    </div>


    <div class="col-md-9 col-md-offset-1 panel panel-default">

                    <div class="panel-body">
            <form action="{{url('/installation')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}

                   <div>
                     <div class="form-group ">
                       <label for="description-field">Title</label>
                         <input class="form-control" name="title" type="text" value="">
                                           </div>

                    <div class="form-group ">
                       <label for="description-field">Description</label>
                        <input class="form-control" name="description" type="text" value="">
                                           </div>
                    <div class="form-group ">
                       <label for="description-field">Price</label>
                        $<input class="form-control" name="price" type="text" value="0.00">
                                           </div>
<input type="hidden" name="Type" value="Installation">

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




@endsection