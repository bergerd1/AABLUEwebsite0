<style>
    .top-head{
        background-color: #00BCD4;
        color:#fff;
    }
    .mg-col{
        margin-left:5px;
    }
    .pd-col{
        padding: 27px 26px;
    }
   .top-head .form-control {
    
    height: 26px;
    border-radius:2px;
    
}
.top-head .btn-link {
    padding:0px;
}
.top-head .btn {
    padding: 2px 19px;
    border-radius: 0px; 
}
</style>
<nav class="navbar navbar top-head">
  <div class="container">
    <div class="col-md-2">
    <div class="navbar-header">
        <?php
$user=DB::select('select * from settings');

?> 
@foreach($user as $value)
      <a class="navbar-brand" href="#"><img src="front/image/{{$value->logo}}"></a>
        @endforeach
    </div>
    </div>
     
   
        <div class="col-md-3">
        <ul class="nav navbar-nav head1 pull-left">
      
       <li><a href="{{ url('login') }}">Real Estate Portal</a></li>
      <li><a href="{{ url('user_register') }}">Sign Up</a></li>
      
      
    </ul> 
    </div>
     <div class="col-md-7">
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="col-md-3 col-md-offset-3">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address</label>

                            
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 mg-col">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class=" control-label">Password</label>

                            
                                <input id="password" type="password" class="form-control" name="password" required>
                               
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--<div class="col-md-2 pd-col">-->
                        <!--<div class="form-group">-->
                        <!--        <div class="checkbox">-->
                        <!--            <label>-->
                        <!--                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me-->
                        <!--            </label>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-md-2 pd-col">
                        <div class="form-group">
                            
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                
                            </div>
                        </div>
                    </form>
    </div>
  
  </div>
</nav>