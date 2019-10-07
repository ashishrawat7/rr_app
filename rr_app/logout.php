<?php
require_once("common/error.php");
require_once('class/class.inc.php');
$sqli_query = new SqlIQuery();

if(!isset($_SESSION['admin_user_login_id'])){
	header("Location: index.php");
	exit();
}else{

	$message=$_SESSION['admin_username']." Logout, IP - ".$_SERVER['REMOTE_ADDR'];
	$sqli_query->writeOnLog($message);
	$sqli_query->logout();
	
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0',false);
	header('Pragma: no-cache'); 
	
	if(!empty($_GET)){
		if($_GET['warning']==1){
			header("Location: index.php?warning=1");#For warning msg for the user that have not authorized to view Dashboard.
			exit();
		}
	}else{
		header("Location: index.php");
		exit();
	}
}
?>
