<?php
require_once('class/error.inc.php');

function error_handler($errno, $errstr, $errfile, $errline) {
	$obj_error_log = new ErrorLog();
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';			
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
	if (SHOW_ERROR==1) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	$obj_error_log->writeOnErrorLog('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
		
	return true;
}

// Error Handler
set_error_handler('error_handler');
?>