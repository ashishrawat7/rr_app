
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet">-->
    <title>Signin Template for Bootstrap</title>
    <!-- Custom styles for this template -->
    <link href="css/login2.css" rel="stylesheet">
  </head>

  <body style="background-color: rgba(126, 123, 215, 0.2);">
    <div class="container" >
      <form class="form-signin">      
        <div class="row bg-danger">
          <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-md-4 col-xs-12 form-group">
              <h3 class="form-signin-heading">Student Login</h3>
              <span class="">Please enter details</span>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-md-4 col-xs-12 form-group">
              <label for="inputEmail" class="sr-only">Email address</label>
              <input type="text" id="inputEmail" class="form-control" placeholder="registration id" required autofocus/>     
          </div>
        </div>

        <div class="row">
          <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-md-4 col-xs-12 form-group">
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-md-4 form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-offset-4 col-sm-offset-4 col-sm-4 col-md-4 form-group">
              <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
          </div>
        </div>
      </form>
      <!-- footer 

      <div class="row footer">
          <div class="col-md-offset-4 col-sm-4" style="height:300px">
          </div>
      </div>
      <div class="row footer">
          <div class="col-md-offset-4 col-sm-4footer">
            <span class="">this is footer</span>       
          </div> 
      </div>
      -->


      <!-- Footer -->
<footer class="page-footer font-small unique-color-dark pt-4">

<!-- Footer Elements -->
<div class="container">

  <!-- Call to action -->
  <ul class="list-unstyled list-inline text-center py-2">
    <li class="list-inline-item">
      <h5 class="mb-1">Register for free</h5>
    </li>
    <li class="list-inline-item">
      <a href="#!" class="btn btn-outline-white btn-rounded">Sign up!</a>
    </li>
  </ul>
  <!-- Call to action -->

</div>
<!-- Footer Elements -->

<!-- Copyright -->
<div class="footer-copyright text-center py-3">© 2019-2020 Copyright:
  <a href="https://mdbootstrap.com/education/bootstrap/"> NeoSoft pvt. ltd.</a>
</div>
<!-- Copyright -->

</footer>
<!-- Footer -->
    </div> <!-- /container -->
  </body>
</html>
