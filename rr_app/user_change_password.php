<?php
require_once("common/error.php");
require_once('class/class.inc.php');
require_once('library/pagination.php');
$sqli_query = new SqlIQuery();

if(!isset($_SESSION['admin_user_login_id'])){
	header("Location: index.php");
	exit();
}

$current_password = $new_password = $confirm_password = "";

if(isset($_POST) && (!empty($_POST))){

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($current_password)){
        $error_current_password = "Please enter current Password!";
    }

    if(empty($confirm_password)){
        $error_confirm_password = "Please enter Confirm Password!";
    }

    if(empty($new_password)){
        $error_new_password = "Please enter New Password!";
    }

    if (($sqli_query->utf8_strlen($new_password) < 8) || ($sqli_query->utf8_strlen($new_password) > 20)) {
		$error_new_password = 'Password must be between 8 to 20 characters!';
	}

    if ($new_password != $confirm_password) {
		$error_confirm_password = 'Password and Confirmation Password do not match!';
	}

    $total = $sqli_query->isValidCurrentPassword($_SESSION['admin_user_login_id'], $current_password);
    if($total == 0){
        $error_current_password = "Incorrect current password!";
    }

    if(!(isset($error_new_password) || isset($error_confirm_password)  || isset($error_current_password) ) ){
        $sqli_query->editPassword($_SESSION['admin_user_login_id'], $new_password);
        $_SESSION['success']="1";
        header('location: success.php?suceess=1');
        exit();
    }
}

$title = $heading = "Change Password";

require_once('common/header.php')             //header?>
 <div class="container-fluid main-container">
    <?php require_once('common/left_menu.php')           //navigation ?>
     <div class="col-md-10 content">
            <div class="panel panel-default">
            <div class="panel-heading"><?php echo $heading ?></div>
            <div class="panel-body">
                <form data-parsley-validate="" id="user_change_password_form" name="user_change_password_form" class="form-horizontal bordered-row" novalidate="" action="user_change_password.php" method="post">
			    <div class="row">
                   <div class="col-md-12">
                        <div class="bdr2">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><span class="font-red">* </span>Current Password</label>
                                <div class="col-sm-5 lline">
                                    <input type="password" class="form-control" name="current_password"  placeholder="************">
                                    <span class="font-red"><?php if(isset($error_current_password)) echo $error_current_password; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 rpad">
                        <div class="bdr2">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><span class="font-red">* </span>New Password</label>
                                <div class="col-sm-8 lline">
                                    <input type="password" class="form-control" name="new_password"  placeholder="************">
                                    <span class="font-red"><?php if(isset($error_new_password)) echo $error_new_password; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 lpad">
                        <div class="bdr2">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><span class="font-red">* </span>Confirm Password</label>
                                <div class="col-sm-8 lline">
                                    <input type="password" class="form-control" name="confirm_password" placeholder="************">
                                    <span class="font-red"><?php if(isset($error_confirm_password)) echo $error_confirm_password; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="space"></div>
                    <div class="col-sm-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="javascript:redirect('success.php');" class="btn btn-primary">Cancel</button>
                    </div>
                </div>
                </form>
            </div>
        </div>        
  	</div>
     <?php require_once('common/footer.php')?>