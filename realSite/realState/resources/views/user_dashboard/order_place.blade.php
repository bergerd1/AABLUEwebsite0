
 @extends('user_dashboard.layouts.layout')
@section('content')


<div style="padding-top:60px"></div>


    <div class="container-fluid">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="page-header">
                    <h1>Your order has been placed!</h1>
                </div>

                <p><a  href="{{url('/order')}}">Your order id is {{$id}} </a> </p>
                 <!--<p><a  href="edit_install/{{$id}}">Your order id is {{$id}}-->
  

                <p>A member of our team will review the order and contact you if further information is required.</p>
                <div class="page-header">
                    <h1>sign(s)</h1>
                   
                </div>
                
                       
                    
                     @foreach($data AS $data_val)
                     <?php if($data_val->template_order_quantity) { ?>
                     <div class="row">

                                    <div class="col-md-4">

                                        <h4>{{ $data_val->temp_name }}</h4>
                                        <img src="../backend/template_picture/{{ $data_val->temp_picture }}"
                                             style="max-width:400px; max-height:200px;"/>

                                    </div>

                                    <div class="col-md-8">
                                                                                                                                                                                                                                                                                                                                                                                                                
                                    </div>

                                </div>
                        <?php } ?>
                       @endforeach
                     
                     
                       <div class="row">
                                <div class="col-md-12">

                                    <h3>Pricing</h3>

                                    <div class="table-responsive">
                                        <table class="table table-striped">

                                            <thead>
                                            <tr>
                                                <th>Template</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>

                                            <tbody class="templates" id="templates">
                                                
                                                <?php $total_tem_cost = 0 ;?>
                                                  @foreach($data AS $data_val)
                                                    <?php if($data_val->template_order_quantity) { ?>
                                                         <tr>
                                                            <td>{{ $data_val->temp_name }}</td>
                                                            <td>{{ $data_val->temp_price }}</td>
                                                            <td>{{ $data_val->template_order_quantity }}</td>
                                                            <td><?php echo $total = $data_val->temp_price * $data_val->template_order_quantity ?></td>
                                                        </tr>
                                                     <?php  $total_tem_cost = $total_tem_cost + $total; ?>  
                                                                                 
                                                      <?php } ?>
                                                   @endforeach
                                                            
                                                             <tr>
                                                            <td colspan="4"><b>Installation Cost</b></td>
                                                           
                                                           
                                                        </tr> 
                                                            
                                                     <?php $total_ins_cost = 0 ;?>
                                               @foreach($datainstall AS $datainstall_val)
                                                    <?php if($datainstall_val->installation_order) { ?>
                                                         <tr>
                                                            <td>{{ $datainstall_val->title }}</td>
                                                            <td>{{ $datainstall_val->price }}</td>
                                                            <td>{{ $datainstall_val->installation_order }}</td>
                                                            <td><?php echo $totalint = $datainstall_val->price * $datainstall_val->installation_order ?></td>
                                                        </tr>
                                                           <?php  $total_ins_cost = $total_ins_cost + $totalint; ?>                          
                                                      <?php } ?>
                                                   @endforeach
                                                   
                                                 
                                                  
                                                   <?php 
                                        
                                                         $grandtotal=$total_tem_cost + $total_ins_cost;
                                                         $tax=5.60;
                                                         $tax_val=$tax /100;
                                                         $Subtotal = $tax_val * $grandtotal;
                                                         $Final= $Subtotal + $grandtotal;
                                                    ?>  
                                                                                          
                                                        <tr>
                                                            <td colspan="3"><b>Tax</b></td>
                                                           
                                                            <td><?php echo $Subtotal; ?></td>
                                                        </tr>  
                                                        
                                                         <tr>
                                                            <td colspan="3"><b>Total</b></td>
                                                           
                                                            <td><?php echo $Final; ?></td>
                                                        </tr>  
                                                
                                            </tbody>

                                        </table>
                                        <form action="{{url('/order_confirmed')}}" method="post" >
                                            {{csrf_field()}}
                                            
                                            <input type="hidden" name="id" value="{{$id}}">
                                       
                                <button type="submit" class="btn btn-success" > Order Confirmd  </button>     
                                        </form>
                                    </div>

                                </div>
                            </div>
                    

            </div>
        </div>

    </div>
    
   
      </hr>


@endsection