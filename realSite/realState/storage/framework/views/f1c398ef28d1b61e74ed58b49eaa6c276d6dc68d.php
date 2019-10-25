   
<!--header nav-->

<?php $__env->startSection('content'); ?>
<div style="padding-top:60px"></div>

<div class="container">

    
    <div class="row">
        <div class="col-sm-12">
            <h1>Install a Sign</h1>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-8">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs install-sign" role="tablist">

                <li role="presentation" class=" previous ">
                    <a  style="cursor: pointer;" onclick="document.getElementById('navStep1').submit()"  aria-controls="home" role="tab">Step 1: <strong>Set Location</strong></a>
                                    </li>

                <li role="presentation" class=" previous ">
                    <a  style="cursor: pointer;" onclick="document.getElementById('navStep2').submit()"  aria-controls="profile" role="tab">Step 2: <strong>Order Signs</strong></a>
                                    </li>

                <li role="presentation" class=" previous ">
                    <a  style="cursor: pointer;" onclick="document.getElementById('navStep3').submit()"  aria-controls="messages" role="tab">Step 3: <strong>Install Options</strong></a>
                                    </li>

                <li role="presentation" class=" active ">
                    <a onclick="document.getElementById('navStep4').submit()" aria-controls="settings" role="tab">Step 4: <strong>Review Order</strong></a>
                </li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active choose-sign-blocks">

                                            <div class="row">
    <div class="col-md-12">

        <h2 class="tab-title">Review your installation order</h2>

        <h3>Below are proofs for your signs. Review carefully before placing your order.</h3>


        <div class="row">
 
<?php $__currentLoopData = $sign_value2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sign_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<?php if($sign_val->template_order_quantity!=0) { ?>            
                 <div class="col-sm-6">
                    <span class="image-title"><?php echo e($sign_val->temp_name); ?></span>
                    <img src="./backend/template_picture/<?php echo e($sign_val->temp_picture); ?>" alt="" class="img-responsive" style="border:1px solid black">
                 </div>
<?php } ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

                                  
            <div class="col-xs-12 special-instructions">

                <label for="special-instructions">Special instructions:</label>

                <textarea form="SubmitForm" rows="4" cols="50" name="instructions"></textarea>

            </div>

        </div>

    </div>
</div>
                    
                </div>
            </div>

        </div>

        <div class="col-md-4 sidebar" style="padding-top:60px">
            <div class="sidebar-inner">

                <h2 class="tab-title">Your Order</h2>

                <div class="row">

                    <div class="col-xs-7">

                        <h4>Installation Location</h4>
<?php $__currentLoopData = $sign_value1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sign_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <span class="address"><?php echo e($sign_val->address); ?></span>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
</div>

                    <div class="col-xs-5">
                        <a style="cursor:pointer;" class="change-location" onclick="document.getElementById('EditAddress').submit()">
                                change
                            </a>
                                            </div>

                </div>

                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">

                    <div class="col-xs-12">
                        <h4>Sign Costs:</h4>
                                                                                <a style="margin-top:inherit;cursor:pointer" class="change-location" onclick="document.getElementById('ChangeSigns').submit()">
                                change
                            </a>
                                            </div>

                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table">

                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                                                    </tr>
                                </thead>

                                <tbody>
 
<?php
$total="";
?>
                                        <?php $__currentLoopData = $sign_value2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sign_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php 
                                        
                                        if($sign_val->template_order_quantity !=0) { ?>
                                        <?php 
                                        
                                        $d1=$sign_val->template_order_quantity*$sign_val->temp_price;
                                       $total+=$d1;
                                        
                                        ?>
                                        <tr>
                                        <td><?php echo e($sign_val->template_order_quantity); ?></td>
                                        <td><?php echo e($sign_val->temp_name); ?></td>
                                        <td class="price-line-item">$<?php echo $d1; ?>.00</td>
                                         </tr>
                                          <?php } ?>
                                        
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                    
                                     
                                
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">

                    <div class="col-xs-12">
                        <h4>Installation Costs</h4>
                   <a style="margin-top:inherit;cursor:pointer;" class="change-location" onclick="document.getElementById('ChangeInstallOptions').submit()">
                                change
                            </a>
                                            </div>

                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table">

                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                                                    </tr>
                                </thead>

                                <tbody>
                                <tr>
                                     <?php
                                      $install_cost=37;
                                      $d2="";
                                       foreach($sign_value2 as  $sign_val)
                                        {
                                      $d2+=$sign_val->template_order_quantity;
                                       
          
                                           }
                                           
                                      ?>
                                        <td><?php echo $d2; ?></td>
                                        
                                        <td>Residential Hanging Post Install</td>
                                        <td class="price-line-item">$<?php echo $install_cost; ?>.00</td>
                                                                            </tr>
                              
                                
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
<?php
$subtotal="";
$sub="";
$sub=$install_cost*$d2;

?>
                
                    <div class="col-xs-12">
                        <hr>
                    </div>

                    <div class="row">

                        <div class="col-xs-12">
                            <h4>Total Cost</h4>
                        </div>

                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th>Qty</th>
                                        <th>Item</th>
                                                                                    <th>Price</th>
                                                                            </tr>
                                    </thead>

                                    <tbody>

                                    <tr>
                                        <td></td>
                                        <td>Subtotal</td>
                                        <td id="subtotal"><?php echo $sub+$total; ?></td>
                                    </tr>
<?php 

 $total1=$sub+$total;;
 $tax=5.60;
 $tax_val=$tax /100;
 $Subtotal=$tax_val*$total1;
 $Final= $Subtotal+$total1;
?>
                                    <tr>
                                        <td>5.60%</td>
                                        <td>Tax</td>
                                        <td id="tax">$<?php echo $Subtotal; ?></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td>Total</td>
                                        <td id="total">$<?php echo $Final; ?></td>
                                    </tr>

                                    
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <form id="SubmitForm" action="<?php echo e(url('order/create')); ?>" class="place-order" method="post">

                               <?php echo e(csrf_field()); ?>



                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>

                                            <td class="align-top">
                                                <input class="agreement-checkbox" onchange="toggleButtonEnabled()" type="checkbox">
                                            </td>

                                            <td class="align-top">
                                                I have viewed the proof(s) and acknowledge that by submitting this order that the sign will be printed as displayed in this proof.
                                            </td>

                                        </tr>

                                        <tr>

                                            <td class="align-top">
                                                <input class="agreement-checkbox" onchange="toggleButtonEnabled()" type="checkbox">
                                            </td>

                                            <td class="align-top">
                                                I have verified with the property owner that there are no shallow underground utilities such as sprinkler lines or electronic pet barriers in the ground where the sign will be displayed.
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>

                                    
                                </div>

                              
                           
  
                                    <button id="placeOrderButton" disabled type="submit" class="btn btn-primary next-step" >Place order</button>

                                 <button onclick="document.getElementById('AddSigns').submit()" type="button" class="btn btn-primary next-step add-more-signs">Add more signs</button>

                                </div>

                            </form>
                        </div>
                    </div>

                
            </div>
        </div>

    </div>

</div>


 <script>
                                            function toggleButtonEnabled() {
                                                var button = $('#placeOrderButton');
                                                if ($('.agreement-checkbox:checked').length === 2) {
                                                    button.attr('disabled',false);
                                                }else {
                                                    button.attr('disabled',true);
                                                }
                                            }
                                        </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('user_dashboard.layouts.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>