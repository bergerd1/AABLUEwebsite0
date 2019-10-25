<!DOCTYPE html>
<html lang="en">
<head>
  <title>WAB</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="backend/css/template.css">
</head>
<body>
<div class="col-sm-8 col-sm-offset-2  border">
    <h2>Create Template</h2>




<form action="{{ url('templates') }}" enctype="multipart/form-data" method="POST">
        {{csrf_field()}}

               
                    
                      <div class="form-group">
            <label>Name Of Template</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label>Description Of Template</label>
            <input type="text" name="description" class="form-control">
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" class="form-control">
        </div>
        <div class="form-group">
            <label>upload</label>
            <input type="file" name="picture" class="form-control">
        </div>
        <div class="form-group">
           <a href="{{url('/templates')}}" class="btn btn-default"> Back<a>
            <button type="submit" class="btn btn-default">Continue</button>
        </div>
            </form>
            


</body>
</html>