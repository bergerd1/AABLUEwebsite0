    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

 
 
   
<div style="padding-top:150px"></div>

  <style>
    .error-msg{
          display: inline-block;
    width: 100%;
    border: 2px solid #F44336;
    box-shadow:0px 0px 10px #666;
    }
    .error-msg h3{
      margin: 0px;
    text-align: center;
    color: #fff;
    background: #F44336;
    padding: 7px;
    font-size: 16px;
    }
    .error-msg p{
      padding: 15px;

    }
    .error-msg span{
      width:100%;
      text-align:center;
      margin-bottom:20px;
      display:inline-block;
    }
  </style>
  
  <div class="container">
      <div class="col-sm-6 col-sm-offset-3">
          <div class="error-msg">
             <h3> <?php $message=  Session::get('message'); echo $message; ?></h3>
              <p>Please Try with another mail Address</p>
              <span><a href="{{url('admin/users')}}" class="btn btn-primary">Try Again</a></span>
          </div>
      </div>
  </div>

