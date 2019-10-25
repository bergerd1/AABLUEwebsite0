 
<?php $__env->startSection('content'); ?>


<div style="padding-top:60px"></div>


    <div class="container-fluid">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="page-header">
                    <h1>Your order has been placed!</h1>
                </div>

                <p><a  href="<?php echo e(url('/order')); ?>">Your order id is <?php echo e($id); ?> </a> </p>
                 <!--<p><a  href="edit_install/<?php echo e($id); ?>">Your order id is <?php echo e($id); ?>-->
  

                <p>A member of our team will review the order and contact you if further information is required.</p>
                <div class="page-header">
                    <h1>sign(s)</h1>
                   
                </div>
                
                       
                    
                     <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <?php if($data_val->template_order_quantity) { ?>
                     <div class="row">

                                    <div class="col-md-4">

                                        <h4><?php echo e($data_val->temp_name); ?></h4>
                                        <img src="../backend/template_picture/<?php echo e($data_val->temp_picture); ?>"
                                             style="max-width:400px; max-height:200px;"/>

                                    </div>

                                    <div class="col-md-8">
                                                                                                                                                                                                                                                                                                                                                                                                                
                                    </div>

                                </div>
                        <?php } ?>
                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     
                     
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
                                                  <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($data_val->template_order_quantity) { ?>
                                                         <tr>
                                                            <td><?php echo e($data_val->temp_name); ?></td>
                                                            <td><?php echo e($data_val->temp_price); ?></td>
                                                            <td><?php echo e($data_val->template_order_quantity); ?></td>
                                                            <td><?php echo $total = $data_val->temp_price * $data_val->template_order_quantity ?></td>
                                                        </tr>
                                                     <?php  $total_tem_cost = $total_tem_cost + $total; ?>  
                                                                                 
                                                      <?php } ?>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            
                                                             <tr>
                                                            <td colspan="4"><b>Installation Cost</b></td>
                                                           
                                                           
                                                        </tr> 
                                                            
                                                     <?php $total_ins_cost = 0 ;?>
                                               <?php $__currentLoopData = $datainstall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $datainstall_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($datainstall_val->installation_order) { ?>
                                                         <tr>
                                                            <td><?php echo e($datainstall_val->title); ?></td>
                                                            <td><?php echo e($datainstall_val->price); ?></td>
                                                            <td><?php echo e($datainstall_val->installation_order); ?></td>
                                                            <td><?php echo $totalint = $datainstall_val->price * $datainstall_val->installation_order ?></td>
                                                        </tr>
                                                           <?php  $total_ins_cost = $total_ins_cost + $totalint; ?>                          
                                                      <?php } ?>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                   
                                                 
                                                  
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
                                        <form action="<?php echo e(url('/order_confirmed')); ?>" method="post" >
                                            <?php echo e(csrf_field()); ?>

                                            
                                            <input type="hidden" name="id" value="<?php echo e($id); ?>">
                                       
                                <button type="submit" class="btn btn-success" > Order Confirmd  </button>     
                                        </form>
                                    </div>

                                </div>
                            </div>
                    

            </div>
        </div>

    </div>
    
   
      </hr>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('user_dashboard.layouts.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>