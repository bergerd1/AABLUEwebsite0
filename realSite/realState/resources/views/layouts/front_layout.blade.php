
  
 <!DOCTYPE html>
<html lang="en">
<head>
      <?php
$user=DB::select('select * from settings');
// echo "<pre>";
// print_r($user); die();
?>
  @foreach($user as $value)
  <title>{{$value->site_title}}</title>
  @endforeach
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="front/css/wab.css">
  <link href="https://fonts.googleapis.com/css?family=Barlow+Condensed" rel="stylesheet">
</head>
<body>

    <!--header code-->
        @include('common.header')
        
        @yield('content')


     
          
          
            <!--Footer-->
    @include('common.footer')
            
</body>
</html>

   