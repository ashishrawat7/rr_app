<?php
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
ob_start();

/* if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
 */
$status = session_status();
if($status == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}elseif($status == PHP_SESSION_DISABLED){
    //Sessions are not available
}/* elseif($status == PHP_SESSION_ACTIVE){
    //Destroy current and start new one
    session_destroy();
    session_start();
}	 */
// HTTP
define('HTTP_ADMIN', 'http://' . $_SERVER['HTTP_HOST'] . '/jinisms/');
define('HTTP_FRONT', 'http://' . $_SERVER['HTTP_HOST']);

// DIR LOGS
define('DIR_LOGS', 'logs/log.txt');
define('DIR_ERROR_LOGS', 'logs/error.txt');

// DB Admin
define('DB_DRIVER', 'mysqliz');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'jinisms');
define('DB_PREFIX', '');

define('SHOW_ERROR', '1');
define('PAGE_LIMIT', '5');
?>