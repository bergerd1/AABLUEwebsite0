    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<div class="container-fluid">
    <div class="row col-md-offset-3 col-md-6">

        <br>

    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Sign Up </h1>
                </div>

                <div class="col-md-9 col-md-offset-1 panel panel-default">

                    <div class="panel-body">
                        <form action="{{ url('/user') }}" method="POST">

                            {{csrf_field()}}

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group ">

                                        <label for="first_name-field">First Name</label>

                                        <input type="text" id="first_name-field" name="first_name" class="form-control" value="" required>

                                    </div>

                                    <div class="form-group ">

                                        <label for="last_name-field">Last Name</label>

                                        <input type="text" id="last_name-field" name="last_name" class="form-control" value="" required>

                                    </div>

                                    <div class="form-group ">

                                        <label for="email-field">Email</label>

                                        <input type="text" id="email-field" name="email" class="form-control" value="" required>

                                    </div>

                                    <div class="form-group ">

                                        <label for="password-field">Password</label>

                                        <input type="password" id="password-field" name="password" class="form-control" value="" required>

                                    </div>

                                    <div class="form-group">

                                        <label for="communication-preferences">Communication Preferences</label>

                                        <!--<p>Send user an email when:</p>-->

                                        <table class="table" id="communication-preferences">

                                            <thead></thead>

                                            <tbody>

                                                <tr>
                                                    <td>An order is</td>
                                                    <td>
                                                        <p>
                                                            <input checked="" type="checkbox" name="order_creation_subscription"> created</p>
                                                        <p>
                                                            <input checked="" type="checkbox" name="order_update_subscription"> updated</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>A removal is</td>
                                                    <td>
                                                        <p>
                                                            <input checked="" type="checkbox" name="removal_creation_subscription"> created</p>
                                                        <p>
                                                            <input checked="" type="checkbox" name="removal_update_subscription"> updated</p>
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group ">

                                        <label for="address-field">Address</label>

                                        <input type="text" id="address-field" name="address" class="form-control" value="">

                                    </div>

                                    <div class="form-group ">

                                        <label for="phone-field">Phone</label>

                                        <input type="text" id="phone-field" name="phone" class="form-control" value="">

                                    </div>

                                    <div class="form-group ">

                                        <label for="fax-field">Fax</label>

                                        <input type="text" id="fax-field" name="fax" class="form-control" value="">

                                    </div>

                                    <div class="form-group ">

                                        <label for="company-field">Assign User To A Company</label>

                                        <select id="company-field" name="company_id" class="form-control" onchange="updateAddress()" required>
                                            <option value="">Select Company</option>
                                             @foreach($company AS $company_val)
                                            <option value="{{$company_val->id}}">{{$company_val->company_name}}</option>
                                        @endforeach

                                        </select>

                                    </div>

                                    <div class="form-group ">

                                        <!--<label for="user_role">Assign User Role</label>-->
                                         <input type="hidden" name="user_role" value="3">
                                        <!--<select name="user_role" class="form-control" required>-->
                        
                                        <!--    <option value="">Select User Role</option>-->
                                        <!--     @foreach($roles AS $roles_val)-->
                                        <!--    <option value="{{$roles_val->id}}">{{$roles_val->roles}}</option>-->
                                        <!--@endforeach-->

                                        <!--</select>-->

                                    </div>

                                </div>

                            </div>

                            <!--<a class="btn btn-default pull-left" href="">Back</a>-->

                            <button class="btn btn-default pull-right" type="submit" name="create" value="1">Create</button>

                            <!--<button class="btn btn-default pull-right" type="" name="customize">Create &amp; Customize Assigned Templates</button>-->

                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- /.container -->
</div>

