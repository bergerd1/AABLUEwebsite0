@extends('user_dashboard.layouts.layout')
<!--header nav-->

@section('content')
<?php 
 $user_id=  Session::get('user_id');
 $company_id=  Session::get('company_name');
 echo $company_id;
 
 echo "vaibhav";
  ?>
  
  
<div style="padding-top:60px"></div>


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="page-header">
                <h1>EULA</h1>
                <small>You must accept the EULA in order to use this site</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
  <?php
$user=DB::select('select * from settings');

?> 

         @foreach($user as $value)
            <div class="well" style="text-align:justify;">{{$value->eula}}</div>
         @endforeach
                <!--            <form action="" method="post">-->

                <!--    <input type="hidden" name="_token" value="rowBcJPVitrN05RQ2xpWcwH7v8AVaJnibtUW1Xka">-->

                <!--    <input type="submit" name="accept-eula" value="I Accept" class="btn btn-block btn-success">-->

                <!--</form>-->
                <form action="{{route('zzz')}}" method="POST">
                    {{csrf_field()}}
                    <input type="checkbox" name="iagree" value="1"> I AGREE
                 <button name="accept-eula" value="I Accept" type="submit" class="btn btn-block btn-success">I Accept</button>
                 </form>
        </div>
    </div>



@endsection