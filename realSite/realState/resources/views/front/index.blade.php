
@extends('layouts.front_layout')

@section('content')

<style>
    .sign-up-body-part{
        background: linear-gradient(white, #b2e5f1ab);
        
    }
   .sign-up-body-part .img-box{
        
    margin-top: 15%;
    }
</style>
<div class="sign-up-body-part">
 <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!--<div class="col-md-6 text-center img-box">-->
                <!--    <img src="http://www.westallisblueprint.com/wp-content/uploads/2017/08/bucksslidercircles.png" alt="" style="width: 479px; height: 433px;" class="text-center">-->
                <!--</div>-->
                <div class="col-md-12">
                <div class="page-header">
                    <h1>Create an account</h1>
                    
                </div>

                <div class=" panel panel-default">

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
    </div>
</div>
<!-- end header section -->
  
  <!--  middle-section  -->
<!--<div class="container-fluid">  -->
<!--<div class="row">  -->
<!--  <div id="myCarousel" class="carousel slide" data-ride="carousel">-->
<!--    <div class="carousel-inner">-->
<!--     <div class="item active">-->
<!--        <div class="col-sm-10 col-sm-offset-1 slide-item1">-->
<!--          <div class="col-sm-6">-->
<!--            <h2>LET YOUR VEHICLES DO THE TALKING </h2>  -->
<!--            <p class="paragrph">Put your fleet of bussiness vechiles to work by covering them in our premium of graphics..work by covering them in our premium of graphics</p>-->
<!--            <a class="butn1 btn btn-primary" href="#">VIEW PROJECT</a>-->
<!--          </div>-->
<!--          <div class="col-sm-6">-->
<!--            <img src="front/image/img1.jpg" alt="Los Angeles">-->
<!--          </div>-->

<!--        </div>-->
<!--      </div>-->

<!--    <div class="item">-->
<!--       <div class="item active">-->
<!--        <div class="col-sm-10 col-sm-offset-1 slide-item1">-->
<!--          <div class="col-sm-6">-->
<!--            <h2>LET YOUR VEHICLES DO THE TALKING </h2>  -->
<!--            <p class="paragrph">Put your fleet of bussiness vechiles to work by covering them in our premium of graphics..work by covering them in our premium of graphics</p>-->
<!--            <a class="butn1 btn btn-primary" href="#">VIEW PROJECT</a>-->
<!--          </div>-->
<!--          <div class="col-sm-6">-->
<!--            <img src="front/image/img2.jpg">-->
<!--          </div>-->

<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--      <div class="item">-->
<!--         <div class="item active">-->
<!--        <div class="col-sm-10 col-sm-offset-1 slide-item1">-->
<!--          <div class="col-sm-6">-->
<!--            <h2>LET YOUR VEHICLES DO THE TALKING </h2>  -->
<!--            <p class="paragrph">Put your fleet of bussiness vechiles to work by covering them in our premium of graphics..work by covering them in our premium of graphics</p>-->
<!--            <a class="butn1 btn btn-primary" href="#">VIEW PROJECT</a>-->
<!--          </div>-->
<!--          <div class="col-sm-6">-->
<!--            <img src="front/image/img3.jpg" alt="Los Angeles">-->
<!--          </div>-->

<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    <a class="left carousel-control" href="#myCarousel" data-slide="prev">-->
<!--      <span class="glyphicon glyphicon-chevron-left"></span>-->
<!--      <span class="sr-only">Previous</span>-->
<!--    </a>-->
<!--    <a class="right carousel-control" href="#myCarousel" data-slide="next">-->
<!--      <span class="glyphicon glyphicon-chevron-right"></span>-->
<!--      <span class="sr-only">Next</span>-->
<!--    </a>-->
<!--  </div>-->
<!--</div>-->

<!--</div>-->
<!--</div>-->

<!-- middle section -->

<!--<div class="container">-->
<!--  <div class="col-sm-12 text-center mid1">-->
<!--    <h2>YOUR SOURCE FOR GRAPHICS</h2>-->
<!--      <p class="parg1">We are here to meet your needs for large format graphics, signs, banners, posters, wall graphics, and more! Tell us your needs and ideas – we’ll source the options that best suit your project. From your concepts to the finished product, we’ll work with you to ensure your vision is realized in your custom designed graphics.</p>-->
<!--  </div>-->
<!--</div>-->

<!-- third section -->
<!--             <div class="container">-->
  
<!--                <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block">-->
<!--                        <img src="front/image/1.jpg" class="img-responsive">-->
<!--                        <div class="bgarea">-->
<!--                        <h2 class="imgtitle">Vehicle Graphics</h2>-->
<!--                        <p class="imgprg">Advertise on the go! Start small with bold vinyl that adheres to anywhere on the vehicle or go all the way with a full vehicle wrap. Either way, gain attention with our long lasting products.</p>-->
<!--                        <a href="#">LEARN MORE</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                 <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block">-->
<!--                        <img src="front/image/2.jpg" class="img-responsive">-->
<!--                        <div class="bgarea">-->
<!--                        <h2 class="imgtitle">Vehicle Graphics</h2>-->
<!--                        <p class="imgprg">Advertise on the go! Start small with bold vinyl that adheres to anywhere on the vehicle or go all the way with a full vehicle wrap. Either way, gain attention with our long lasting products.</p>-->
<!--                        <a href="#">LEARN MORE</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->
              
<!--                <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block">-->
<!--                        <img src="front/image/3.jpg" class="img-responsive">-->
<!--                        <div class="bgarea">-->
<!--                        <h2 class="imgtitle">Vehicle Graphics</h2>-->
<!--                        <p class="imgprg">Advertise on the go! Start small with bold vinyl that adheres to anywhere on the vehicle or go all the way with a full vehicle wrap. Either way, gain attention with our long lasting products.</p>-->
<!--                        <a href="#">LEARN MORE</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--          </div>-->
<!--      </div>-->


<!-- fourth section -->

<!--<div class="container-fluid">-->
<!--  <div class="row">-->
<!--    <div class="forimg">-->
<!--      <div class="col-sm-10 col-sm-offset-1">-->
<!--          <h2 class="headprg">WHY CHOOSE US</h2>-->
<!--      <p class="why-text">Quality is the code that we live by. We hand picked optimal equipment to exceed industry standards and deliver you the-->
<!--sharpest, most unique, and stunning products. Your satisfaction is what keeps our company growing and thriving.</p>-->
<!--      <p><img src="front/image/circles.jpg"></p>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->

 <!-- fifth section -->
<!-- <div class="col-sm-12  mailimg">-->
<!--      <div class="container">-->
<!--         <div class="col-sm-4">-->
<!--           <p><img src="front/image/air.jpg" class="img-responsive"></p>-->
<!--         </div> -->
<!--    <div class="col-sm-8">-->
<!--      <h3 class="textprg">REQUEST A FREE QUOTE</h3>-->
<!--      <p class="textprg1">Curious how much your project will cost? Wondering how long it would take to have it in your hands? Contact us for a FREE quote! West Allis Blue will provide a quote ASAP.</p>-->
<!--    </div>-->
<!--</div>-->

<!--</div>-->
<!-- sixth section -->
<!--<div class="container">-->
<!--  <div class="col-sm-12 text-center mid1">-->
<!--    <h2 class="text1end">ENDLESS POSSIBILITES IN PRINT</h2>-->
<!--      <p class="parg1">We are here to meet your needs for large format graphics, signs, banners, posters, wall graphics, and more! Tell us your needs and ideas – we’ll source the options that best suit your project. From your concepts to the finished product, we’ll work with you to ensure your vision is realized in your custom designed graphics.</p>-->
<!--  </div>-->
<!--</div>-->

<!-- 7section -->
<!--              <div class="container">-->
  
<!--                <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec1.jpg" class="img-thumbnail">-->
<!--                        <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                     <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec2.jpg" class="img-thumbnail">-->
<!--                          <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div> <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec3.jpg" class="img-thumbnail">-->
<!--                          <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div>-->

<!--              <div class="col-sm-4">-->
<!--                  <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec4.jpg" class="img-thumbnail">-->
<!--                          <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                     <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec5.jpg" class="img-thumbnail">-->
<!--                          <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div> -->
<!--                    <div class="col-sm-4">-->
<!--                    <div class="col-sm-12 team-block1">-->
<!--                        <img src="front/image/sec6.jpg" class="img-thumbnail">-->
<!--                          <h3 class="sevntext">EXTERIOR WALL COVERINGS</h3>-->
<!--                      <p class="sevnprg">  Products to cover any exterior surface   with digital prints.</p>-->
<!--                      <a href="#">Learn More</a>-->
<!--                      </div>-->
<!--                    </div>-->
<!--                  </div>-->


<!-- egthsection -->
<!-- <div class="col-sm-12 imgsec1">-->
<!--      <div class="container">-->
<!--          <div class="col-sm-4">-->
<!--             <img src="front/image/pencil.jpg" >-->
<!--              <p>Over 100,000 Projects Completed</p>-->
<!--          </div>-->
<!--      <div class="col-sm-4">-->
<!--            <img src="front/image/user.jpg" >-->
<!--            <p>Over 1,000 Satisfied Clients</p>-->
<!--    </div> -->
<!--    <div class="col-sm-4">-->
<!--          <img src="front/image/print.jpg" >-->
<!--          <p>Over 500 Machines In the Field</p>-->
<!--      </div>-->
<!--    </div>-->
<!--</div>-->


<!--<div class="container client-logo">-->
<!--      <div class="col-sm-8 col-sm-offset-2">-->
<!--              <div class="col-sm-3">-->
<!--                <img src="front/image/logo1.png" class="logo img-responsive">-->
<!--              </div>-->
<!--              <div class="col-sm-3">-->
<!--               <img src="front/image/logo2.png" class="logo img-responsive">-->
<!--              </div>-->
<!--              <div class="col-sm-3">-->
<!--               <img src="front/image/logo3.png" class="logo img-responsive">-->
<!--              </div>        -->
<!--              <div class="col-sm-3">-->
<!--               <img src="front/image/logo4.png" class="logo img-responsive">-->
<!--              </div>-->
<!--      </div>      -->
<!--</div>-->


<!-- <div class="col-sm-12 career-form">-->
<!--    <div class="container">-->
<!--        <h3 class="text-center">GET IN TOUCH</h3>-->
<!--                <p>Want more information? Have a question? Send us a message and we will respond as soon as possible.If you have a print order, please send it directly to repro@westallisblue.com..</p>-->
<!--            <form>-->
<!--                <div class="form-group">-->
<!--                    <div class="col-xs-6 col-md-6">-->
<!--                         <input id="phone" name="text" class="form-control input-lg" placeholder="Name*" type="text">-->
<!--                    </div>-->
<!--                    <div class="col-xs-6 col-md-6">-->
<!--                       <input id="phone" name="text" class="form-control input-lg" placeholder="Email*" type="text">-->
<!--                    </div>-->
<!--                </div>-->
<!--                 <div class="form-group">-->
<!--                     <div class="col-xs-6 col-md-6">-->
<!--                        <input id="phone" name="text" class="form-control input-lg" placeholder="Phone*" type="text">-->
<!--                    </div>-->
<!--                     <div class="col-xs-6 col-md-6">-->
<!--                       <input id="phone" name="text" class="form-control input-lg" placeholder=" Bussiness*" type="text">-->
<!--                    </div>-->
<!--                </div>-->
                      
<!--                   <div class="form-group">-->
<!--                        <div class="col-sm-6">-->
<!--                            <textarea class="form-control" rows="5"></textarea>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                <div class="form-group submit-btn">    -->
<!--                   <input id="submit" name="submit" class="btn btn-primary btn-lg"  value="Submit" type="submit">-->
<!--                 </div>-->
<!--        </form> -->
<!--    </div>-->
<!--</div>-->
    









        
        @endsection