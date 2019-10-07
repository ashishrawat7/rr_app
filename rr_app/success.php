<?php
require_once("common/error.php");
require_once('class/class.inc.php');
require_once('library/pagination.php');
$sqli_query = new SqlIQuery();

if(!isset($_SESSION['admin_user_login_id'])){
	header("Location: index.php");
	exit();
}
$title = $heading = "Success";

if(isset($_SESSION['success']) && $_SESSION['success']=='1'){
	$success="1";
	$_SESSION['success']='';
	unset($_SESSION['success']);
}


require_once('common/header.php')             //header?>
 <div class="container-fluid main-container">
    <?php require_once('common/left_menu.php')           //navigation ?>
     <div class="col-md-10 content">
            <div class="panel panel-default">
            <div class="panel-heading"><?php echo $heading ?></div>
            <div class="panel-body">
				<?php if(isset($success)){ ?>
                   <div class="alert alert-success">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Success!</strong> Password Changed!
                   </div>
                <?php } ?>

                <h2>Wellcome</h2><h4>Recent activity</h4>
                
                <span class="">Last login: <?php if($_SESSION['admin_user_last_login_date'] == '0000-00-00 00:00:00'){
                    $user_last_login_date = 'First Time Login';}
                    else{
                        $user_last_login_date = date('j F, Y, g:i A', strtotime($_SESSION['admin_user_last_login_date']));
						}echo "<strong>".$user_last_login_date."</strong>";?></span>
                <span><?php if(!empty($_SESSION['admin_user_last_ip'])){echo "IP: ".$_SESSION['admin_user_last_ip'];} ?></span>                
            </div>
        </div>        
  	</div>
     <?php require_once('common/footer.php')?>
  	