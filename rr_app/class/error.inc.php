<?php
require_once('startup.php');
class ErrorLog {
	
	private $error_filename;
		
	public function __construct() {		
		$this->error_filename = DIR_ERROR_LOGS;
	}	
	
	/*Start Error Log*/
	public function writeOnErrorLog($message) {
		$file = $this->error_filename;		
		$handle = fopen($file, 'a+');		
		fwrite($handle, date('Y-m-d H:i:s') . ' - ' . $message . "\n");			
		fclose($handle); 
	}

	public function readErrorLog() {
		$file = $this->error_filename;		
		if (file_exists($file)) { $log = file_get_contents($file, FILE_USE_INCLUDE_PATH, null); } else { $log = ''; }
		return $log;
	}

	public function clearErrorLog() {
		$file = $this->error_filename;		
		$handle = fopen($file, 'w+');				
		fclose($handle);		
		return 1;				
	}
	/*End Error Log*/

}/*End Class*/
?>