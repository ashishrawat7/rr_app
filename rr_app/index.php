<?php
require_once("common/error.php");
require_once('class/class.inc.php');
$sqli_query = new SqlIQuery();

if(isset($_SESSION['admin_user_login_id'])){
	header("Location: success.php");
	exit();
}
$username = "";


if(isset($_POST['username'])){

	$username=$_POST['username'];
	
	if(empty($_POST['username']) || empty($_POST['password'])){
		$error_warning ='Blank Username and/or Password.';		
	}else{
		
		$data=$sqli_query->login($_POST['username'],$_POST['password']);			

		if($data){
			$message = $_SESSION['admin_username']." Login, IP - ".$_SERVER['REMOTE_ADDR'];
			$sqli_query->writeOnLog($message);						
			header("Location: success.php");
			exit();
		}else{
			$error_warning='No match for Username and/or Password.';
			$message=$_POST['username']." Attempting to invalid Login, IP - ".$_SERVER['REMOTE_ADDR'];
			$sqli_query->writeOnLog($message);
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">   
    <title>Admin Pannel</title>    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">    
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="container">
      <form class="">      
        <div class="row">
          <div class="col-md-offset-4 col-sm-4 form-group">
              <h1 class="heading">Shuats</h1>
              <h3 class="sub-heading">Welcome in back/incomplete exam portal</h3>
              
                <!-- Call to action -->
                <ul class="list-unstyled list-inline text-center py-2">
                    <li class="list-inline-item">
                        <a href="student_login.php" class="btn btn-outline-white btn-rounded">Student Registration</a>               
                    </li>
                    <li class="list-inline-item">
                        <a href="student_login.php" class="btn btn-outline-white btn-rounded">Student Log in</a>
                    </li>
                </ul>              
            <!-- Call to action -->             
          </div>
        </div>

        <footer class="page-footer font-small unique-color-dark pt-4">
            <!-- Footer Elements -->
            <!-- Footer Elements -->
            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">© 2018 Copyright: <a href="https://mdbootstrap.com/education/bootstrap/"> MDBootstrap.com</a></div>
            <!-- Copyright -->
        </footer>
    </div> <!-- /container -->
  </body>
</html>