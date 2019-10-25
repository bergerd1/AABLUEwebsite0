  @extends('admin.layouts.backend_layout')
<!--header nav-->
 

@section('content')

<div class="container-fluid">
    <div class="row col-md-offset-3 col-md-6">

        <br>
<br/>
<br/>
        
        
        
    </div>
</div>

    <div class="container top-head">
      <div class="row">
         <div class="col-sm-12">
            <h2>Installation and Removal Costs </h2>
        <div class="pull-right pd-top">
        <a class="btn btn-success  " href="{{url('create_installation')}}">Create Installation</a><nobr></nobr>
        <a class="btn btn-success  installation-btn" href="{{url('create_removal')}}">Create Removal</a>
         </div>
        </div>
      </div>
   </div>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
     <div class="table-responsive ">
      <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="30%">Title</th>
                        <th width="30%">Description</th>
                        <th width="10%">Price</th>
                        <th width="10%">Type</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>

                <tbody>
@foreach($inst_rem as  $value) 
                                <tr>
                    <td>{{$value->title}}</td>
                    <td>{{$value->description}}</td>
                    <td>{{$value->price}}</td>
                    <td> {{$value->Type}} </td>
                    <td class="text-right">
                        <a class="btn btn-default " href="edit_value/{{$value->id}}">Edit</a>
                            <a class="btn btn-danger" href="delete_value/{{$value->id }}" type="submit">Delete</a>
                                               

                    </td>
                </tr>
                  @endforeach 


                                </tbody>
            </table>

      </div>
    
    </div>


</div>
        </div>
    



@endsection