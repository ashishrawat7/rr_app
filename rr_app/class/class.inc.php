<?php
require_once('startup.php');
date_default_timezone_set("Asia/Kolkata");
class SqlIQuery {

	/*Start ERP code*/
	#######################################################################################################
	
	private $db;
	private $filename;
	private $permission = array();
	
	public function __construct() {		
		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);		
		$this->filename = DIR_LOGS;
	}
	/*Log file*/
	public function writeOnLog($message) {
		$file = $this->filename;		
		$handle = fopen($file, 'a+');		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");			
		fclose($handle); 
	}
	public function readLog() {
		$file = $this->filename;		
		if (file_exists($file)) { $log = file_get_contents($file, FILE_USE_INCLUDE_PATH, null); } else { $log = ''; }
		return $log;
	}
	public function clearLog() {
		$file = $this->filename;		
		$handle = fopen($file, 'w+');				
		fclose($handle);		
		return 1;				
	}
	/*Admin Login*/
	public function login($username, $password) {
		$salt = substr(sha1($password), 0, 9);
    	$user_query = $this->db->query("SELECT u.*,g.permission, TO_DAYS(NOW()) - TO_DAYS(u.last_pwd_changed) AS total_days, g.group_name AS group_name FROM admin_user u JOIN admin_user_group g ON(g.user_group_id=u.user_group_id) WHERE BINARY u.username = '" . $this->db->escape($username) . "' AND u.password = '" . $this->db->escape(md5($password)) . "' AND u.salt = '" . $this->db->escape($salt) . "' AND u.status = '1'");

    	if ($user_query->num_rows) {

			$_SESSION['admin_user_login_id']		= $user_query->row['user_id'];			
			$_SESSION['admin_username']				= $user_query->row['username'];
			$_SESSION['admin_user_group_id']		= $user_query->row['user_group_id'];			
			$_SESSION['admin_user_last_ip']			= $user_query->row['ip'];
			$_SESSION['admin_user_last_login_date'] = $user_query->row['login_date'];
			$_SESSION['admin_menu_permission']		= $user_query->row['permission'];
			$_SESSION['admin_phone']				= $user_query->row['phone'];		
			$_SESSION['admin_email']				= $user_query->row['email'];
		    #$_SESSION['admin_total_days']			= $user_query->row['total_days'];
			$_SESSION['admin_firstname']			= $user_query->row['firstname'];
			$_SESSION['admin_lastname']				= $user_query->row['lastname'];
			$_SESSION['admin_group_name']			= $user_query->row['group_name'];

			$this->db->query("UPDATE admin_user SET ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "', login_date = NOW() WHERE user_id = '" . (int)$_SESSION['admin_user_login_id'] . "'");#Update User IP & login date			

			$user_group_query = $this->db->query("SELECT m.*,ug2m.permission FROM admin_module m LEFT JOIN admin_user_group_to_module ug2m ON(ug2m.module_id=m.module_id) WHERE ug2m.user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");				
			
			$_SESSION['admin_permissions'] = $user_group_query->rows;
			
      		return true;      		
    	} else {
      		return false;
    	}
  	}

    public function getPermission($module_code) {
		$permissions = $_SESSION['admin_permissions'];		
		foreach($permissions as $permission){
			if($permission['module_code'] == $module_code){
				return true;
			}
		}		
		return false;
  	}



	/*public function getPermission($page) {
		$permissions = $_SESSION['admin_permissions'];		
		foreach($permissions as $permission){
			if($permission['module_code']==$page){
				$authorities = explode(",",$permission['permission']);
				$flagW=0;
				$flagR=0;
				foreach($authorities as $authority){
					if($authority=='W'){
						$flagW=1;			
					}elseif($authority=='R'){
						$flagR=1;
					}				
				}
				if($flagW==1 && $flagR==1){
					return true;				
				}else{
					return false;
				}
			}
		}		
		return false;
  	}*/

	/*Admin Logout*/
  	public function logout() {		
		unset($_SESSION['admin_user_login_id']);
		unset($_SESSION['admin_username']);		
		unset($_SESSION['admin_user_group_id']);
		unset($_SESSION['admin_user_last_ip']);		
		unset($_SESSION['admin_user_last_login_date']);
		unset($_SESSION['admin_menu_permission']);
		unset($_SESSION['admin_permissions']);
		unset($_SESSION['admin_phone']);
		unset($_SESSION['admin_email']);		
		unset($_SESSION['admin_total_days']);
		unset($_SESSION['admin_firstname']);
		unset($_SESSION['admin_lastname']);
		unset($_SESSION['admin_group_name']);
		session_destroy();
  	}

   /*Start Module*/
   public function getTotalModule($filter_status,$searchString) {
		$sql = "SELECT COUNT(*) AS total FROM admin_module AS e  WHERE 1";
		if($filter_status == 'inactive' ){
			$sql .= " AND e.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND e.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (e.module_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.module_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.updated_date LIKE '%" . $this->db->escape($searchString) . "%')";			
		}
		
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}

	public function getModule($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT * FROM admin_module AS e WHERE 1";

		if($filter_status == 'inactive' ){
			$sql .= " AND e.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND e.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (e.module_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.module_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.updated_date LIKE '%" . $this->db->escape($searchString) . "%')";			
		}

		$sql .= " ORDER BY e.module_id ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}

    public function getAllModule() {
            $sql = "SELECT module_id, module_name, module_code FROM admin_module ORDER BY module_name";
		    $query = $this->db->query($sql);					
		    return $query->rows;
	}
    public function getModulePermissionsByGroupId($user_group_id) {
            $sql = "SELECT module_id,permission FROM admin_user_group_to_module WHERE user_group_id = '".(int)$user_group_id."'";
		    $query = $this->db->query($sql);					
		    return $query->rows;
	}

    public function addModule($data) {
	    $this->db->query("INSERT INTO admin_module SET module_name = '" . $this->db->escape($this->utf8_str_trim($data['modulename'])) . "', module_code ='" . $this->db->escape($this->utf8_str_trim($data['modulecode'])) . "', add_date=NOW()");
    }
    public function editModule($data) {
	    $this->db->query("UPDATE admin_module SET module_name = '" . $this->db->escape($this->utf8_str_trim($data['module_name'])) . "', module_code = '" .
        $this->db->escape($this->utf8_str_trim($data['module_code'])) . "', updated_date=NOW() WHERE module_id = '" . (int)$data['module_id'] . "'");
    }

    public function getTotalModuleNameByModuleCode($module_code) {
        $query = $this->db->query("SELECT count(*) AS total FROM admin_module WHERE module_code = '". $this->db->escape($module_code)."'");
        return $query->row['total'];
	}

	public function getTotalModuleNameByModuleName($module_name) {
        $query = $this->db->query("SELECT count(*) AS total FROM admin_module WHERE module_name = '". $this->db->escape($module_name)."'");
        return $query->row['total'];
	}

    public function getModuleNameByModuleCode($module_code) {
        $query = $this->db->query("SELECT module_id FROM admin_module WHERE module_code = '". $this->db->escape($module_code)."'");
		return $query->rows;
    }

   /*End Module*/

   /*Start user group*/
   public function getTotalUserGroup($filter_status,$searchString) {
		$sql = "SELECT COUNT(*) AS total FROM admin_user_group AS ug WHERE user_group_id > 1";
		
        if($filter_status == 'inactive' ){
			$sql .= " AND ug.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND ug.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (ug.group_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.updated_date LIKE '%" . $this->db->escape($searchString) . "%')";			
		}
		
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}

	public function getUserGroup($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT * FROM admin_user_group AS ug WHERE user_group_id > 1";

		if($filter_status == 'inactive' ){
			$sql .= " AND ug.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND ug.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (ug.group_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.updated_date LIKE '%" . $this->db->escape($searchString) . "%')";			
		}

		$sql .= " ORDER BY ug.user_group_id ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
    public function getUserGroupById($user_group_id) {
		$sql = "SELECT * FROM admin_user_group WHERE user_group_id = '" . (int)$user_group_id . "' AND  user_group_id > 0";
		$query = $this->db->query($sql);					
		return $query->row;
	}

    public function getModuleById($module_id) {
		$sql = "SELECT * FROM admin_module WHERE module_id = '".(int)$module_id."'";
		$query = $this->db->query($sql);					
		return $query->row;
	}

    public function addUserGroup($data) {
	    $this->db->query("INSERT INTO admin_user_group SET group_name = '" . $this->db->escape($this->utf8_str_trim($data ['group_name'])) . "',
		status = '" . (int)$data ['status'] . "',add_date=NOW()");
        $user_group_id = $this->db->getLastId();

        $permissions = $data['permissions'];
        foreach($permissions as $permission){
           $this->db->query("INSERT INTO admin_user_group_to_module SET user_group_id = '" . $user_group_id . "', module_id = '" . $permission . "'");
        }
        
    }
    public function editUserGroup($data) {
	    $this->db->query("UPDATE admin_user_group SET group_name = '" . $this->db->escape($this->utf8_str_trim($data['group_name'])) . "',
		status = '" . (int)$data['status'] . "',
		updated_date = now() WHERE user_group_id = '".$data['user_group_id']."'");
        
        $this->db->query("DELETE FROM `admin_user_group_to_module` WHERE user_group_id = '". (int)$data['user_group_id'] ."' ");
        $permissions = $data['permissions'];
        foreach($permissions as $permission){
           $this->db->query("INSERT INTO admin_user_group_to_module SET user_group_id = '" . $data['user_group_id'] . "', module_id = '" . $permission . "'");
        }
    }

    public function isExist($module_id, $permissions){
         foreach($permissions as $permission){
             if($permission['module_id'] == $module_id){
                 return TRUE;
             }
         }
		return FALSE;
    }

	public function getTotalUserGroupNameByGroupName($user_group_name) {
		$query = $this->db->query("SELECT count(*) AS total FROM admin_user_group WHERE group_name = '". $this->db->escape($user_group_name)."'");
		return $query->row['total'];
	}

    public function getUserGroupNameByGroupName($user_group_name) {
        $query = $this->db->query("SELECT user_group_id, group_name FROM admin_user_group WHERE group_name = '". $this->db->escape($user_group_name)."'");
     return $query->rows;
	}
   /*End user group*/


   /*Start User*/
   public function getTotalUser($filter_status,$searchString) {
		$sql = "SELECT COUNT(*) AS total FROM admin_user AS au LEFT JOIN admin_user_group AS ug ON (au.user_group_id = ug.user_group_id) WHERE  user_id > 1";
		if($filter_status == 'inactive' ){
			$sql .= " AND au.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND au.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (au.user_id LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.username LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR CONCAT(au.firstname,' ',au.lastname) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.email LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.phone LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.group_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR au.date_added LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR au.date_updated LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}
    
	public function getUser($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT au.*,CONCAT(au.firstname,' ',au.lastname) AS full_name, ug.group_name FROM admin_user AS au LEFT JOIN admin_user_group AS ug ON (au.user_group_id = ug.user_group_id) WHERE  user_id > 1";

		if($filter_status == 'inactive' ){
			$sql .= " AND au.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND au.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (au.user_id LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.username LIKE '%" . $this->db->escape($searchString) . "%'";
            
            $sql .= " OR CONCAT(au.firstname,' ',au.lastname) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.email LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR au.phone LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ug.group_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR au.date_added LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR au.date_updated LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY au.user_id ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
    
    public function getAllGroups() {
            $sql = "SELECT user_group_id, group_name, permission FROM admin_user_group WHERE user_group_id > 1 ORDER BY group_name ";
		    $query = $this->db->query($sql);					
		    return $query->rows;
	}
   
    public function addUser($data) {
        $salt = substr(sha1($data['password']), 0, 9);
	    $this->db->query("INSERT INTO admin_user SET user_group_id = '" . $this->db->escape($this->utf8_str_trim($data['select_group'])) . "',
		username ='" . $this->db->escape($this->utf8_str_trim($data['username'])) . "',password = '" .
		$this->db->escape(md5($data['password'])) . "', salt = '" . $this->db->escape($salt) . "', firstname ='" . $this->db->escape($this->utf8_str_trim($data['firstname'])) 
		. "',lastname ='" . $this->db->escape($this->utf8_str_trim($data['lastname'])) . "',email ='" . 
		$this->db->escape($this->utf8_str_trim($data['email'])) . "',status ='" . $this->db->escape($this->utf8_str_trim($data['status'])) . "',
		phone ='" . $this->db->escape($this->utf8_str_trim($data['phone'])) . "',date_added=NOW()");


    }

    public function getUserById($user_id) {
		$sql = "SELECT * FROM admin_user WHERE user_id = '".(int)$user_id."' AND user_id > 1";
		$query = $this->db->query($sql);					
		return $query->row;
	}

    public function editUser($data) {

	    $qry = "UPDATE admin_user SET user_group_id = '" . $this->db->escape($this->utf8_str_trim($data['user_group_id'])) . "',
         username ='" . $this->db->escape($this->utf8_str_trim($data['username']))."', firstname ='" . $this->db->escape($this->utf8_str_trim($data             ['firstname'])). "',lastname ='" . $this->db->escape($this->utf8_str_trim($data['lastname'])) . "',email ='" . 
          $this->db->escape($this->utf8_str_trim($data['email'])) . "',status ='" . $this->db->escape($this->utf8_str_trim($data['status'])) . "',
          phone ='" . $this->db->escape($this->utf8_str_trim($data['phone'])) . "', date_updated=NOW() WHERE user_id = '" . (int)$data['user_id'] . "'";
          
          if ($data['password']) {
			$this->editPassword($data['user_id'], $data['password']);		
		}
          $this->db->query($qry);

    }

    public function getTotalSuperUser($top_admin_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM admin_user WHERE user_group_id = '" . (int)$top_admin_id . "'");		
		return $query->row['total'];
	}

	public function editPassword($user_id, $password) {
		$salt = substr(sha1($password), 0, 9);
		
		if($this->db->query("UPDATE admin_user SET password = '" . $this->db->escape(md5($password)) . "', salt = '" . $this->db->escape($salt) . "', last_pwd_changed = NOW() WHERE user_id = '" . (int)$user_id . "'"))
			return TRUE;
        return FALSE;
    }

	public function isValidCurrentPassword($user_id, $password) {
        $salt = substr(sha1($password), 0, 9);
        $query = $this->db->query("SELECT count(*) AS total FROM admin_user WHERE user_id = ".(int)$user_id. " AND password = '".
        $this->db->escape(md5($password))."' AND salt = '".$salt."'");
            
        return $query->row['total'];
	}

    public function getTotalUserNameByName($user_name) {
        $query = $this->db->query("SELECT count(*) AS total FROM admin_user WHERE username = '". $this->db->escape($user_name)."'");
        return $query->row['total'];
	}

     public function getUserNameByName($user_name) {
        $query = $this->db->query("SELECT user_id FROM admin_user WHERE username = '". $this->db->escape($user_name)."'");
     return $query->rows;
     }

     
   /*End User*/

   /*Start Student*/
   public function getTotalStudent($filter_status,$searchString) {
		$sql = "SELECT COUNT(*) AS total FROM student WHERE 1";
        $searchString = preg_replace('/\s+/', '', $searchString);
		if($filter_status == 'inactive' ){
			$sql .= " AND status=2";
		}elseif($filter_status == 'active' ){
			$sql .= " AND status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (registration_no LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR CONCAT(first_name,middle_name,last_name) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR course_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR contact_no LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR father_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR dob LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}
    
	public function getStudent($start,$limit,$filter_status,$searchString) {
		$searchString = preg_replace('/\s+/', '', $searchString);
		$sql = "SELECT student_id, registration_no, CONCAT(first_name,' ', middle_name,' ', last_name) AS full_name, father_name, contact_no, dob, course_code, status FROM student WHERE 1";


		if($filter_status == 'inactive' ){
			$sql .= " AND status=2";
		}elseif($filter_status == 'active' ){
			$sql .= " AND status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (registration_no LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR CONCAT(first_name,middle_name,last_name) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR course_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR contact_no LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR father_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR dob LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY registration_no ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
   
    public function getAllCourse() {
		$sql = "SELECT course_id, course_code, course_name FROM course ORDER BY course_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}

    public function getSettingValueByKey($setting_key) {
         $query = $this->db->query("SELECT value FROM settings WHERE setting_key LIKE '" . $this->db->escape($setting_key) . "'");
         return $query->row['value'];
    }

    public function addStudent($data) {
        $student_registration_string = $this->getSettingValueByKey('student_registration_string');
        $student_registration_start = $this->getSettingValueByKey('student_registration_start');

        $registration_no = $student_registration_string.date('y').($student_registration_start + 1);
        
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));
		$data['admission_date'] = date('Y-m-d', strtotime($data['admission_date']));

	    $this->db->query("INSERT INTO student SET registration_no = '".$registration_no."', first_name = '" . $this->db->escape($this->utf8_str_trim($data['first_name'])) . "',middle_name ='" . $this->db->escape($this->utf8_str_trim($data['middle_name'])) . "',last_name = '" .$this->db->escape($this->utf8_str_trim($data['last_name'])) . "', father_name = '" . $this->db->escape($this->utf8_str_trim($data['father_name'])) . "',mother_name = '" . $this->db->escape($this->utf8_str_trim($data['mother_name'])) . "',occupation = '" . $this->db->escape($this->utf8_str_trim($data['occupation'])) . "',cota_id = '" . $this->db->escape($this->utf8_str_trim($data['cota_id'])) . "',section_id = '" . $this->db->escape($this->utf8_str_trim($data['section_id'])) . "',dob = '" . $this->db->escape($data['dob']) . ",gender = '" . (int)$data['gender'] . ",blood_group = '" . (int)$data['blood_group'] . ",category = '" . (int)$data['category'] . ",contact_no = '" . $this->db->escape($data['contact_no']) . "',email = '" . $this->db->escape($this->utf8_str_trim($data['email'])) . "',course_code = '" . $this->db->escape($data['course_code']). "',aadhaar_no = '" . $this->db->escape($this->utf8_str_trim($data['aadhaar_no'])) . "',status = '" . (int)$data['status'] . "',admission_date = '" . $this->db->escape($data['admission_date']) . "', add_date=NOW()"); $student_id = $this->db->getLastId();

        $this->db->query("INSERT INTO student_address SET 
        student_id = '".$student_id."',
        mailing_address = '" . $this->db->escape($this->utf8_str_trim($data['mailing_address'])) . "',
        m_town_village ='" . $this->db->escape($this->utf8_str_trim($data['m_town_village'])) . "',
        m_city = '" .$this->db->escape($this->utf8_str_trim($data['m_city'])) . "', 
        m_pin = '" . (int)$data['m_pin'] . "',  
        m_state = '" . $this->db->escape($this->utf8_str_trim($data['m_state'])) . "' ,
        permanent_address = '" . $this->db->escape($data['permanent_address']) . "', 
        p_town_village = '" . $this->db->escape($data['p_town_village']) . "', 
        p_city = '" . $this->db->escape($this->utf8_str_trim($data['p_city'])) . "', 
        p_pin = '" . (int)$data['p_pin'] . "', 
        p_state = '" . $this->db->escape($data['p_state'])."'");


        #increament one in registraion_start
        $qry = "UPDATE `settings` SET `value` = " . ($student_registration_start + 1). " WHERE  setting_key = 'student_registration_start'";
        $this->db->query($qry);
		return $student_id;
    }

	public function getStudentRegistrationImageById($student_id) {
		$qry = "SELECT registration_no, image from student WHERE student_id = ".(int)$student_id;
		$query = $this->db->query($qry);				
		return $query->row;
	}

	public function studentUploadImage($student_id, $image_filename) {
		$qry = "UPDATE student SET image = '".$this->db->escape($this->utf8_str_trim($image_filename))."' WHERE student_id = ".(int)$student_id;
		$query = $this->db->query($qry);
	}
    

    public function getStudentById($student_id) {
		$sql = "SELECT s.*,sc.name,ct.cota_name ,c.course_name, c.course_code, sa.mailing_address, sa.m_town_village, sa.m_city, sa.m_pin, sa.m_state, sa.permanent_address, sa.p_town_village,
         sa.p_city, sa.p_pin, sa.p_state FROM student AS s LEFT JOIN student_address AS sa ON (s.student_id = sa.student_id)
		 LEFT JOIN cota AS ct ON (s.cota_id = ct.cota_id)
		 LEFT JOIN course AS c ON (s.course_code = c.course_code)
		  LEFT JOIN section AS sc ON (s.section_id = sc.section_id)WHERE s.student_id = '".(int)$student_id."'";
		$query = $this->db->query($sql);					
		return $query->row;
	}
    
    public function updateStudent($data) {

        $data['dob'] = $this->dateDMYToYMD($data['dob']);
        $data['admission_date'] = $this->dateDMYToYMD($data['admission_date']);

	    $qry = "UPDATE student SET 
        first_name = '" . $this->db->escape($this->utf8_str_trim($data['first_name'])) . "',
        middle_name ='" . $this->db->escape($this->utf8_str_trim($data['middle_name'])) . "',
        last_name = '" .$this->db->escape($this->utf8_str_trim($data['last_name'])) . "', 
        father_name = '" . $this->db->escape($this->utf8_str_trim($data['father_name'])) . "',  
        mother_name = '" . $this->db->escape($this->utf8_str_trim($data['mother_name'])) . "' ,
		occupation = '" . $this->db->escape($this->utf8_str_trim($data['occupation'])) . "' ,
		section_id = '" . (int)$data['section_id'] . "' ,
		cota_id = '" . (int)$data['cota_id'] . "' ,
        dob = '" . $this->db->escape($data['dob']) . "', 
        gender = '" . (int)$data['gender'] . "', 
        blood_group = '" . (int)$data['blood_group'] . "', 
        category = '" . (int)$data['category'] . "', 
        contact_no = '" . $this->db->escape($data['contact_no']) . "', 
        email = '" . $this->db->escape($this->utf8_str_trim($data['email'])) . "', 
        course_code = '" . $this->db->escape($this->utf8_str_trim($data['course_code'])). "', 
        aadhaar_no = '" . $this->db->escape($this->utf8_str_trim($data['aadhaar_no'])) . "',
        status = '" . (int)$data['status'] . "',
		admission_date = '" . $this->db->escape($data['admission_date']) . "', 
        update_date= NOW() WHERE student_id = '".$data['student_id']."'";

        $this->db->query($qry);    

        $qry = "UPDATE student_address SET 
        mailing_address = '" . $this->db->escape($this->utf8_str_trim($data['mailing_address'])) . "',
        m_town_village ='" . $this->db->escape($this->utf8_str_trim($data['m_town_village'])) . "',
        m_city = '" .$this->db->escape($this->utf8_str_trim($data['m_city'])) . "', 
        m_pin = '" . (int)$data['m_pin'] . "',  
        m_state = '" . $this->db->escape($this->utf8_str_trim($data['m_state'])) . "' ,
        permanent_address = '" . $this->db->escape($data['permanent_address']) . "', 
        p_town_village = '" . $this->db->escape($data['p_town_village']) . "', 
        p_city = '" . $this->db->escape($this->utf8_str_trim($data['p_city'])) . "', 
        p_pin = '" . (int)$data['p_pin'] . "', 
        p_state = '" . $this->db->escape($data['p_state'])."'
         WHERE student_id = '".(int)$data['student_id']."'";
        $this->db->query($qry);
    }
    public function getAllFeeByStudentId($student_id){
		$sql = "SELECT due, month_of, month_of_2, fee_id, fee_amount, recipt_no, payable_amount, paid_amount, add_date FROM fee WHERE student_id = ".(int)$student_id ." ORDER BY fee_id";
		$query = $this->db->query($sql);
		return $query->rows;
	}
    public function getStudentFeeById($student_id) {
		
		$sql = "SELECT s.student_id, ct.cota_id,ct.cota_name, sc.name, s.add_date, s.section_id, s.blood_group, s.contact_no, s.registration_no, s.first_name, s.last_name, s.middle_name, s.father_name, s.dob, s.category, s.course_code , c.course_name FROM student AS s LEFT JOIN course AS c ON (s.course_code = c.course_code) 
		LEFT JOIN cota AS ct ON (s.cota_id = ct.cota_id) LEFT JOIN section AS sc ON (s.section_id = sc.section_id)
		WHERE s.student_id = ".(int)$student_id;
		$query = $this->db->query($sql);
        
		if(isset($query->row) && (!empty($query->row))){
			$data = $query->row;

			$result['student_id'] = $data['student_id'];
			$result['registration_no'] = $data['registration_no'];
			$result['first_name'] = $data['first_name'];
			$result['middle_name'] = $data['middle_name'];
			$result['last_name'] = $data['last_name'];
			$result['father_name'] = $data['father_name'];
			$result['dob'] = $data['dob'];
			$result['category'] = $data['category'];
			$result['course_code'] = $data['course_code'];
			$result['course_name'] = $data['course_name'];

			$result['cota_name'] = $data['cota_name'];
			$result['cota_id'] = $data['cota_id'];
			$result['name'] = $data['name'];
			$result['add_date'] = $data['add_date'];

			$sql = "SELECT due, month_of, recipt_no, payable_amount, paid_amount FROM fee WHERE student_id = ".(int)$student_id ." ORDER BY fee_id DESC limit 0,1";
			$query = $this->db->query($sql);

			if(!empty($query->row)){
				$data = $query->row;
				$result['due'] = $data['due'];
				$result['month_of'] = $data['month_of'];
				$result['recipt_no'] = $data['recipt_no'];
				$result['payable_amount'] = $data['payable_amount'];
				$result['paid_amount'] = $data['paid_amount'];
			}
			return $result;
		}else return;
	}
	
	public function getStudentFeeByIdForReciept($fee_id) {
		
		$sql = "SELECT s.student_id, f.due, f.month_of, f.recipt_no, f.payable_amount, f.paid_amount, f.month_of, f.month_of_2, ct.cota_id,ct.cota_name, sc.name, s.add_date, s.section_id, s.registration_no, s.first_name, s.last_name, s.middle_name, s.father_name, s.category, s.course_code, c.course_name FROM fee AS f LEFT JOIN student AS s ON (s.student_id = f.student_id) LEFT JOIN cota AS ct ON (s.cota_id = ct.cota_id) LEFT JOIN section AS sc ON (s.section_id = sc.section_id) LEFT JOIN course AS c ON (s.course_code = c.course_code) WHERE f.fee_id = ".(int)$fee_id;
		$query = $this->db->query($sql);
        
		if(isset($query->row) && (!empty($query->row))){
			return $query->row;
		}
		return;
	}

	public function payFee($_DATA) {
		if(!isset($_DATA['fee_month'][1])) {
			$_DATA['fee_month'][1] = 0;
		}

		$fee_recipt_no = ($this->getSettingValueByKey('fee_recipt_no') + 1);
		$sql = "INSERT INTO fee SET  student_id = '" .(int)$_DATA['student_id'] . "',
		recipt_no ='" .(int)$fee_recipt_no . "', 
		course_code ='" .$_DATA['course_code'] . "', 
		fee_amount = '" .$this->db->escape($_DATA['fee']) . "', 
		concession = '" . $this->db->escape($this->utf8_str_trim($_DATA['concession'])) . "', 
		late_fee ='" . $this->db->escape($this->utf8_str_trim($_DATA['late_fee'])) . "', 
		payable_amount = '" .  $this->db->escape($this->utf8_str_trim($_DATA['total_payable_amount']))  . "', 
		paid_amount = '" . $this->db->escape($this->utf8_str_trim($_DATA['payment'])) . "', 
		due = '" .  $this->db->escape($this->utf8_str_trim($_DATA['due'])) . "', 
		month_of = '" . $this->db->escape($_DATA['fee_month']['0']) . "', 
		month_of_2 = '" . $this->db->escape($_DATA['fee_month']['1']) . "', 
		add_date = now()";
		$query = $this->db->query($sql);

		$last_fee_id = $this->db->getLastId();		
		

		#increament one in fee_recipt_no

		$qry = "UPDATE `settings` SET `value` = " . $fee_recipt_no. " WHERE  setting_key = 'fee_recipt_no'";
		$this->db->query($qry);

	return $last_fee_id;
    }

	public function getStudentIcardById($student_id) {
		
		$sql = "SELECT s.student_id, s.blood_group, sc.name, s.contact_no, s.registration_no, s.first_name, s.last_name, s.middle_name, s.father_name, s.dob , c.course_name, sa.mailing_address FROM student AS s LEFT JOIN course AS c ON (s.course_code = c.course_code) LEFT JOIN student_address AS sa ON (s.student_id = sa.student_id) LEFT JOIN section AS sc ON (s.section_id = sc.section_id) WHERE s.student_id = ".(int)$student_id;
        
		$query = $this->db->query($sql);
        
		return  $query->row;
	} 

	public function getSetttingValueByKey($setting_key) {
         $query = $this->db->query("SELECT value FROM settings WHERE setting_key LIKE '" . $this->db->escape($setting_key) . "'");
         return $query->row['value'];
    }

	public function getStudentListByClassAndSection($course_code, $section_id){
		 $query = $this->db->query("SELECT student_id, registration_no, first_name, last_name, middle_name FROM student 
		 WHERE course_code = '".$course_code."' AND section_id = ".$section_id );
		return $query->rows;
	}

	public function studentAttendanceFill($data){
		$attendance_date = date('Y-m-d', strtotime($_POST['attendance_date']));
		$query = $this->db->query("INSERT INTO student_attendance SET 
		attendance_date = '".$attendance_date."',
		course_code = '".$data['course_code']."',
		section_id = ".(int)$data['section_id']);
		
		$attendance_id = $this->db->getLastId();
		
		foreach($_POST['student_id'] AS $student_id){
			if(isset($_POST[$student_id])){
				$query = $this->db->query("INSERT INTO student_to_attendance SET 
				attendance_id = " .(int)$attendance_id.",
				student_id = ".(int)$student_id.",
				status = ".(int)$_POST[$student_id].",
				add_date = NOW()"
				);
			}
		}
	}
	
	
	public function isValidAttendanceDate($attendance_date, $course_code, $section_id){
		$attendance_date = date('Y-m-d', strtotime($attendance_date));
		$query = $this->db->query("SELECT count(*) AS total FROM student_attendance
		WHERE attendance_date = '".$attendance_date. "' AND course_code = '".$course_code."' AND section_id = ".(int)$section_id);
		return $query->row['total'];
	}

	
	public function getStudentByRegistration($registration_no) {		

		$sql = "SELECT s.student_id, sc.name, s.registration_no, CONCAT(s.first_name,' ',s.middle_name,' ',s.last_name) AS full_name, s.father_name, s.dob, c.course_name FROM student AS s LEFT JOIN course AS c ON (s.course_code = c.course_code) LEFT JOIN cota AS ct ON (s.cota_id = ct.cota_id) LEFT JOIN section AS sc ON (s.section_id = sc.section_id)	WHERE s.registration_no =  '". $this->db->escape($this->utf8_str_trim($registration_no))."'";
		$query = $this->db->query($sql);					
		return $query->rows;
	}

	public function getStudentsByCourseCode($course_code) {		

		$sql = "SELECT student_id, registration_no, CONCAT(first_name,' ',middle_name,' ',last_name) AS full_name FROM student WHERE course_code =  '". $this->db->escape($this->utf8_str_trim($course_code))."'";
		$query = $this->db->query($sql);					
		return $query->rows;
	}

	public function getStudentByNameClassSection($student_name, $course_id, $section_id) {
		$sql = "SELECT s.student_id, sc.name, s.registration_no, CONCAT(s.first_name,' ',s.middle_name,' ',s.last_name) AS full_name, s.father_name, s.dob, c.course_name FROM student AS s LEFT JOIN course AS c ON (s.course_id = c.course_id) LEFT JOIN cota AS ct ON (s.cota_id = ct.cota_id) LEFT JOIN section AS sc ON (s.section_id = sc.section_id) WHERE CONCAT(s.first_name,s.middle_name,s.last_name) LIKE '". $this->db->escape($this->utf8_str_trim($student_name))."' AND s.course_id = ".(int)$course_id; 
		if($section_id !=0 ){
			$sql .= " AND s.section_id = ".(int)$section_id;
		}
		 
		$query = $this->db->query($sql);					
		return $query->rows;
	}
    

	/***  start course  ***/
	public function getTotalCourse($filter_status,$searchString) {
		$sql = "SELECT COUNT(*) AS total FROM course AS c  WHERE 1";
        
		if($filter_status == 'inactive' ){
			$sql .= " AND c.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND c.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (c.course_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR c.course_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}
    
	public function getCourse($start,$limit,$filter_status,$searchString) {
		$sql = "SELECT c.course_id, c.course_code, c.course_name, c.add_date, c.update_date, c.status FROM course AS c WHERE 1";

		if($filter_status == 'inactive' ){
			$sql .= " AND c.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND c.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (c.course_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR c.course_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$sql .= " ORDER BY c.course_id ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}


	public function getCourseById($course_id) {
	    $query = $this->db->query("SELECT c.course_id, c.course_code, c.course_name, c.add_date, c.update_date, c.status FROM course as c WHERE c.course_id = '". $this->db->escape($course_id)."'");
		return $query->row;
    }

	public function getCourseByCode($course_code) {
	    $query = $this->db->query("SELECT course_name FROM course WHERE course_code = '". $this->db->escape($course_code)."'");
		return $query->row;
    }

	public function getTotalCourseNameByName($course_name) {
        $query = $this->db->query("SELECT count(*) as total FROM course WHERE course_name = '". $this->db->escape($course_name)."'");
     return $query->row['total'];
	}

	public function getCourseNameByName($course_name) {
        $query = $this->db->query("SELECT course_id FROM course WHERE course_name = '". $this->db->escape($course_name)."'");
     return $query->rows;
    }

	public function getCourseNameById($course_id) {
        $query = $this->db->query("SELECT course_name FROM course WHERE course_id = '". $this->db->escape($course_id)."'");
     return $query->row['course_name'];
    }

	public function editCourse($data) {
	    $this->db->query("UPDATE course SET 
		course_code = '" . $this->db->escape($this->utf8_str_trim($data['course_code'])) . "',
		course_name = '" . $this->db->escape($this->utf8_str_trim($data['course_name'])) . "',
		status = '" . (int)$data['status'] . "',
		update_date = NOW()
		WHERE course_id = '".$data['course_id']."'");
    }

	public function addCourse($data) {
	    $this->db->query("INSERT INTO course SET
        course_code = '" . $this->db->escape($this->utf8_str_trim($data['course_code'])) . "',course_name = '" . $this->db->escape($this->utf8_str_trim($data['course_name'])) . "',add_date = NOW(),status = ".(int)$data['status']);
    }

	public function setSubjectOfClass($data, $session) {
		$this->db->query("delete from course_to_subject WHERE course_code ='".$data['course_code']."' AND session_year = '".$session."'");
		foreach($data['subject'] AS $subject){
			$this->db->query("INSERT INTO course_to_subject SET	course_code = '" .$this->db->escape($this->utf8_str_trim($data['course_code'])) . "', subject_code = '" .$this->db->escape($this->utf8_str_trim($subject))."', session_year = '" .$this->db->escape($this->utf8_str_trim($session))."'");
		}
    }
	
	public function getAllsection() {
	    $query = $this->db->query("SELECT section_id, name FROM section WHERE 1");
		return $query->rows;
    }

	public function studentFeeHistory($student_id, $course_code) {
	    $query = $this->db->query("SELECT month_of, month_of_2 FROM fee WHERE student_id = '".(int)$student_id."' AND course_code = '".$course_code."'");
		return $query->rows;
    }

	public function isExistFeeMonth($c_month, $fee_history){
		$flag = false;
		
		foreach($fee_history as $month){
			if((int)$month['month_of'] == (int)$c_month){
				$flag = true;
			}
		}
		if(!$flag){
			foreach($fee_history as $month){
				if((int)$month['month_of_2'] == (int)$c_month){
					$flag = true;
				}
			}
		}
		return $flag;
    }

	/*** End course ***/


	/****************************      End Student      ******************************/

	/****************************    Start Employee    *******************************/

   public function getTotalEmployee($filter_status,$searchString) {
		
		$sql = "SELECT COUNT(*) AS total FROM employee AS e LEFT JOIN designation AS d ON (e.designation_id = d.designation_id) WHERE 1";
		$searchString = preg_replace('/\s+/', '', $searchString);
		if($filter_status == 'inactive' ){
			$sql .= " AND e.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND e.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (e.employee_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR CONCAT(e.first_name,e.middle_name,e.last_name) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR e.email LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR e.contact_no LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR d.designation_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}
    
	public function getEmployee($start,$limit,$filter_status,$searchString) {
		$searchString = preg_replace('/\s+/', '', $searchString);
		$sql = "SELECT CONCAT(e.first_name,' ',e.middle_name,' ',e.last_name) AS full_name, e.employee_code, e.email, e.contact_no, e.add_date, e.update_date, e.status, e.employee_id, d.designation_name FROM employee AS e LEFT JOIN designation AS d ON (e.designation_id = d.designation_id) WHERE 1";

		if($filter_status == 'inactive' ){
			$sql .= " AND e.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND e.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (e.employee_code LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR CONCAT(e.first_name,e.middle_name,e.last_name) LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR e.email LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR e.contact_no LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR d.designation_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR e.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY e.employee_code ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
    

   
    public function addEmployee($data) {
		$registration_string = $this->getSettingValueByKey('employee_registration_string');
        $registration_start = $this->getSettingValueByKey('employee_registration_start');

        $employee_code = $registration_string. ($registration_start + 1);

         
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));
        $data['join_date'] = date('Y-m-d', strtotime($data['join_date']));

	    $this->db->query("INSERT INTO employee SET 
        employee_code = '".$employee_code."',
        first_name = '" . $this->db->escape($this->utf8_str_trim($data['first_name'])) . "',
        middle_name ='" . $this->db->escape($this->utf8_str_trim($data['middle_name'])) . "',
        last_name = '" .$this->db->escape($this->utf8_str_trim($data['last_name'])) . "', 
        father_name = '" . $this->db->escape($this->utf8_str_trim($data['father_name'])) . "',  
        mother_name = '" . $this->db->escape($this->utf8_str_trim($data['mother_name'])) . "' ,
        dob = '" . $this->db->escape($data['dob']) . "', 
        gender = '" . (int)$data['gender'] . "', 
        blood_group = '" . (int)$data['blood_group'] . "', 
        category = '" . (int)$data['category'] . "', 
        contact_no = '" . $this->db->escape($data['contact_no']) . "', 
        email = '" . $this->db->escape($this->utf8_str_trim($data['email'])) . "', 
        designation_id = '" . (int)$data['designation_id']. "', 
		marital_status = '" . (int)$data['marital_status']. "', 
        aadhaar_no = '" . $this->db->escape($this->utf8_str_trim($data['aadhaar_no'])) . "',
		pan_no = '" . $this->db->escape($this->utf8_str_trim($data['pan_no'])) . "',
		join_date = '" . $this->db->escape($data['join_date']) . "', 
        status = '" . (int)$data['status'] . "',
        add_date=NOW()");

        $employee_id = $this->db->getLastId();

        $this->db->query("INSERT INTO employee_address SET 
        employee_id = '".$employee_id."',
        mailing_address = '" . $this->db->escape($this->utf8_str_trim($data['mailing_address'])) . "',
        m_town_village ='" . $this->db->escape($this->utf8_str_trim($data['m_town_village'])) . "',
        m_city = '" .$this->db->escape($this->utf8_str_trim($data['m_city'])) . "', 
        m_pin = '" . (int)$data['m_pin'] . "',  
        m_state = '" . $this->db->escape($this->utf8_str_trim($data['m_state'])) . "' ,
        permanent_address = '" . $this->db->escape($data['permanent_address']) . "', 
        p_town_village = '" . $this->db->escape($data['p_town_village']) . "', 
        p_city = '" . $this->db->escape($this->utf8_str_trim($data['p_city'])) . "', 
        p_pin = '" . (int)$data['p_pin'] . "', 
        p_state = '" . $this->db->escape($data['p_state'])."'");
		
			#default salary
		$query = $this->db->query("INSERT INTO employee_default_salary SET employee_id = ".$employee_id.", basic = 0,hra = 0,special_allowance = 0,conveyance_allowance = 0, education_allowance = 0, medical_allowance = 0, mobile_allowance = 0, internet = 0, pf = 0, esi = 0, professional_tax = 0, tds = 0, employer_pf = 0, status = '1',add_date = NOW()");

            #increament one in registraion_start
        $qry = "UPDATE `settings` SET `value` = " . ($registration_start + 1). " WHERE  setting_key = 'employee_registration_start'";
        $this->db->query($qry);
    }


    public function getEmployeeById($employee_id) {
		$sql = "SELECT e.*, ea.mailing_address, ea.m_town_village, ea.m_city, ea.m_pin, ea.m_state, ea.permanent_address, ea.p_town_village,
         ea.p_city, ea.p_pin, ea.p_state, d.designation_name FROM employee AS e 
		 LEFT JOIN employee_address AS ea ON (e.employee_id = ea.employee_id) 
 		 LEFT JOIN designation AS d ON (e.designation_id = d.designation_id)
		 
		 WHERE e.employee_id = '".(int)$employee_id."'";
		
		
		$query = $this->db->query($sql);					
		return $query->row;		
	}

	public function editEmployee($data) {
        $data['dob'] = date('Y-m-d', strtotime($data['dob']));
		$data['join_date'] = date('Y-m-d', strtotime($data['join_date']));
        
	    $qry = "UPDATE employee SET 
        first_name = '" . $this->db->escape($this->utf8_str_trim($data['first_name'])) . "',
        middle_name ='" . $this->db->escape($this->utf8_str_trim($data['middle_name'])) . "',
        last_name = '" .$this->db->escape($this->utf8_str_trim($data['last_name'])) . "', 
        father_name = '" . $this->db->escape($this->utf8_str_trim($data['father_name'])) . "',  
        mother_name = '" . $this->db->escape($this->utf8_str_trim($data['mother_name'])) . "' ,
        dob = '" . $this->db->escape($data['dob']) . "', 
        gender = '" . (int)$data['gender'] . "', 
        blood_group = '" . (int)$data['blood_group'] . "', 
        category = '" . (int)$data['category'] . "', 
        contact_no = '" . $this->db->escape($data['contact_no']) . "', 
        email = '" . $this->db->escape($this->utf8_str_trim($data['email'])) . "', 
        designation_id = '" . (int)$data['designation_id']. "', 
		marital_status = '" . (int)$data['marital_status']. "', 
        aadhaar_no = '" . $this->db->escape($this->utf8_str_trim($data['aadhaar_no'])) . "',
		pan_no = '" . $this->db->escape($this->utf8_str_trim($data['pan_no'])) . "',
		join_date = '" . $this->db->escape($data['join_date']) . "', 
        status = '" . (int)$data['status'] . "',
        update_date=NOW() WHERE employee_id = '".(int)$data['employee_id']."'";
        $this->db->query($qry);
    

        $qry = "UPDATE employee_address SET 
        mailing_address = '" . $this->db->escape($this->utf8_str_trim($data['mailing_address'])) . "',
        m_town_village ='" . $this->db->escape($this->utf8_str_trim($data['m_town_village'])) . "',
        m_city = '" .$this->db->escape($this->utf8_str_trim($data['m_city'])) . "', 
        m_pin = '" . (int)$data['m_pin'] . "',  
        m_state = '" . $this->db->escape($this->utf8_str_trim($data['m_state'])) . "' ,
        permanent_address = '" . $this->db->escape($data['permanent_address']) . "', 
        p_town_village = '" . $this->db->escape($data['p_town_village']) . "', 
        p_city = '" . $this->db->escape($this->utf8_str_trim($data['p_city'])) . "', 
        p_pin = '" . (int)$data['p_pin'] . "', 
        p_state = '" . $this->db->escape($data['p_state'])."'
         WHERE employee_id = '".(int)$data['employee_id']."'";
        $this->db->query($qry);
    }

	public function getEmployeeByRegistration($employee_code) {
		$sql = "SELECT e.employee_id, e.employee_code, CONCAT(e.first_name,' ',e.middle_name,' ',e.last_name) AS full_name, e.father_name, e.dob, d.designation_name FROM employee AS e LEFT JOIN designation AS d ON (e.designation_id = d.designation_id)	WHERE e.employee_code =  '". $this->db->escape($this->utf8_str_trim($employee_code))."'";
		$query = $this->db->query($sql);					
		return $query->rows;
	}

	public function getEmployeeByNameDesignation($employee_name, $designation_id) {
		$sql = "SELECT e.employee_id, e.employee_code, CONCAT(e.first_name,' ',e.middle_name,' ',e.last_name) AS full_name, e.father_name, e.dob, d.designation_name FROM employee AS e LEFT JOIN designation AS d ON (e.designation_id = d.designation_id) WHERE CONCAT(e.first_name,e.middle_name,e.last_name) LIKE '". $this->db->escape($this->utf8_str_trim($employee_name))."' AND e.designation_id = ".(int)$designation_id;
		$query = $this->db->query($sql);	
		return $query->rows;
	}

	public function getEmployeeListByDepartmant($designation_id) {
		$sql = "SELECT employee_id, employee_code, CONCAT(first_name,' ',middle_name,' ',last_name) AS full_name, father_name, dob FROM employee WHERE designation_id = ".(int)$designation_id;
		$query = $this->db->query($sql);	
		return $query->rows;
	}
	
	/******************       Start Employee Salary       ******************/

	
	public function addEmployeeSalary($data) {
		$qry = "UPDATE employee_default_salary SET status = 0, update_date=NOW() WHERE employee_id = ".(int)$data['employee_id']." AND status = 1";
        $this->db->query($qry);
		
		$query = $this->db->query("INSERT INTO employee_default_salary SET employee_id = '".(int)$data['employee_id']."', basic = '".(int)$data['basic_salary']."',hra = '".(int)$data['hra']."',special_allowance = '".(int)$data['special_allowance']."',conveyance_allowance = '".(int)$data['conveyance_allowance']."',education_allowance = '".(int)$data['education_allowance']."', medical_allowance = '".(int)$data['medical_allowance']."',mobile_allowance = '".(int)$data['mobile_allowance']."',internet = '".(int)$data['internet']."',pf = '".(int)$data['pf']."',esi = '".(int)$data['esi']."',professional_tax = '".(int)$data['professional_tax']."',tds = '".(int)$data['tds']."',employer_pf = '".(int)$data['employer_pf']."',status = '1',add_date = NOW(), update_date = NOW()");
	}

	public function getSalaryByEmployeeId($employee_id) {
		
		$qry = "SELECT default_salary_id, basic, hra, special_allowance, conveyance_allowance, education_allowance, medical_allowance, mobile_allowance, internet, pf, esi, professional_tax, tds, employer_pf, status, add_date, update_date , add_date, update_date FROM employee_default_salary WHERE employee_id = ".(int)$employee_id." AND status = 1";
        $query = $this->db->query($qry);
		$result = $query->row;
		
		if(empty($result)){
			$result['default_salary_id'] = '';
			$result['basic'] = '';
			$result['hra'] = '';
			$result['special_allowance'] = '';
			$result['conveyance_allowance'] = '';
			$result['education_allowance'] = '';
			$result['medical_allowance'] = '';
			$result['mobile_allowance'] = '';
			$result['internet'] = '';
			$result['pf'] = '';
			$result['esi'] = '';
			$result['professional_tax'] = '';
			$result['tds'] = '';
			$result['employer_pf'] = '';
			$result['status'] = '';
			$result['add_date'] = '';
			$result['update_date'] = '';
		}

		$qry = "SELECT employee_code from employee WHERE employee_id = ".(int)$employee_id;
		$query = $this->db->query($qry);		
		if(empty($query->row['employee_code'])){
			return;
		}else{
			$result['employee_code'] = $query->row['employee_code'];
			return $result;
		}
	}

	public function payEmployeeSalary($employee_id, $from_date, $to_date, $no_of_days, $total_days, $salary) {
		$to_date = $this->dateDMYToYMD($to_date);
		$from_date = $this->dateDMYToYMD($from_date);

		$query = $this->db->query("INSERT INTO employee_salary_details SET employee_id = '".(int)$employee_id."', to_date = '".$to_date."', from_date = '".$from_date."',	basic = ".(int)$salary['basic'].",hra = ".(int)$salary['hra'].",special_allowance = ".(int)$salary['special_allowance'].",conveyance_allowance = ".(int)$salary['conveyance_allowance'].",education_allowance = ".(int)$salary['education_allowance'].", medical_allowance = ".(int)$salary['medical_allowance'].",mobile_allowance = ".(int)$salary['mobile_allowance'].",internet = ".(int)$salary['internet'].",pf = ".(int)$salary['pf'].",esi = ".(int)$salary['esi'].",professional_tax = ".(int)$salary['professional_tax'].",tds = ".(int)$salary['tds'].",no_of_days = ".(int)$no_of_days.",total_earnings =  ".(int)$salary['total_earnings'].", total_deductions = ".(int)$salary['total_deductions'].", net_pay= ".(int)$salary['net_pay'].", add_date = NOW()");
		return $this->db->getLastId();
	}

	
	public function getEmployeeSalaryDetailsBySalaryId($salary_id) {
		$query = $this->db->query("SELECT e.employee_code, concat(e.first_name,' ', e.middle_name,' ',e.last_name) AS full_name, es.salary_id, es.employee_id, es.to_date, es.from_date, es.basic,	es.hra, es.special_allowance, es.conveyance_allowance, es.education_allowance, es.medical_allowance, es.mobile_allowance, es.internet, es.pf, es.esi, es.professional_tax, es.tds, es.no_of_days, es.total_earnings, es.total_deductions, es.net_pay , d.designation_name, es.add_date FROM employee_salary_details AS es LEFT JOIN employee AS e ON (es.employee_id = e.employee_id) LEFT JOIN designation AS d ON (e.designation_id = d.designation_id)  WHERE es.salary_id = '".(int)$salary_id."'");
		return $query->row; 
	}

	public function getTotalSalaryDetailsById($employee_id) {
		
		$sql = "SELECT COUNT(*) AS total FROM employee_salary_details WHERE employee_id = ".$employee_id;		
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}

	public function getEmployeeSalaryByEmployeeId($start,$limit, $employee_id) {
		
		$sql = "SELECT salary_id, to_date, from_date, net_pay, add_date FROM employee_salary_details WHERE employee_id = ".(int)$employee_id." ORDER BY salary_id DESC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
	/******************       End  Employee Salary       ******************/
	/******************          End Employee            ******************/


	/*******************	START DESIGNATION    *******************/
	public function GetDesignations() {
		$query = $this->db->query("SELECT designation_id, designation_name FROM designation WHERE 1");
		return $query->rows;
	}
	/*******************	END DESIGNATION *******************/

	/*******************	START FEE - BREAKUP  *******************/
	
	public function setFeeStructure($data) {
		$session_year = $this->getCurrentSession();
		$freq = $data['freq'];
		$fee = $data['fee'];
		$size = count($fee);
		$i = 1;

		while($i <= $size){
			
			if(($freq[$i] != '0') && ($freq[$i] != '6')){
				$this->db->query("INSERT INTO fee_structure SET 
				fee_breakup_id = '" .$i. "',
				course_id = '" . (int)$data['course_id'] . "',
				frequency = '" . (int)$freq[$i] . "',
				fee = '" . (int)$fee[$i] . "',
				status = '1',
				session_year = '".$session_year."',
				add_date=NOW()");
			}
			$i++;
		}
    }

	public function editFeeStructure($data, $course_id, $session_year) {
		$freq = $data['freq'];
		$fee = $data['fee'];
		$size = count($fee);
		$i = 1;

		$this->db->query("delete from fee_structure where course_id = '".$course_id."' AND session_year = '".$session_year."'");
		
		while($i <= $size){
			
			if(($freq[$i] != '0') && ($freq[$i] != '99')){
				$this->db->query("INSERT INTO fee_structure SET fee_breakup_id = '" .$i. "', course_id = '" . (int)$data['course_id'] . "', frequency = '" . (int)$freq[$i] . "', fee = '" . (int)$fee[$i] . "', status = '1', session_year = '".$session_year."', add_date=NOW()");
			}
			$i++;
		}
    }

	public function isExistFeeStructure($course_id, $session_year) {
		$sql = "SELECT COUNT(*) AS total FROM fee_structure WHERE course_id = '".$course_id."' AND session_year = '".$session_year."'";
		$query = $this->db->query($sql);
		return $query->row['total'];
    }


	public function getTotalFeeBreakup($filter_status,$searchString) {
		
		$sql = "SELECT COUNT(*) AS total FROM fee_breakup AS fb WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= " AND fb.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND fb.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){
			$sql .= " AND (fb.fee_breakup_id LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR fb.fee_breakup_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR fb.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR fb.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function editFeeBreakup($data) {

	    $qry = "UPDATE fee_breakup SET 
        fee_breakup_name = '" . $this->db->escape($this->utf8_str_trim($data['fee_breakup_name'])) . "',         
        update_date=NOW() WHERE fee_breakup_id = '".(int)$data['fee_breakup_id']."'";
        $this->db->query($qry);

    }
    
	public function getFeeBreakup($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT fb.fee_breakup_id, fb.fee_breakup_name, fb.add_date, fb.update_date FROM fee_breakup AS fb WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= " AND fb.status=0";
		}elseif($filter_status == 'active'){
			$sql .= " AND fb.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){			
            $sql .= " AND (fb.fee_breakup_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR fb.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR fb.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY fb.add_date ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
	
	public function getAllFeeBreakup() {
		
		$sql = "SELECT fee_breakup_id, fee_breakup_name FROM fee_breakup WHERE 1 ORDER BY fee_breakup_name ASC";
		$query = $this->db->query($sql);					
		return $query->rows;
	}

	public function getFeeBreakupsByFeeId($fee_id) { 
		$sql = "SELECT fb.fee_breakup_name, fm.fee
		FROM fee_to_feebreak_up AS fm left join fee_breakup AS fb ON (fm.feebreak_up_id = fb.fee_breakup_id) WHERE fm.fee_id = '".(int)$fee_id."' ORDER BY fb.fee_breakup_name ASC";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function addFeeBreakup($data) {
		
	    $this->db->query("INSERT INTO fee_breakup SET 
		fee_breakup_name = '" . $this->db->escape($this->utf8_str_trim($data['fee_breakup_name'])) . "', add_date=NOW()");
    }


	public function getFeeBreakupById($fee_breakup_id) {

	    $query = $this->db->query("SELECT fee_breakup_id, fee_breakup_name FROM fee_breakup WHERE fee_breakup_id = ".(int)$fee_breakup_id);
		return $query->row;

    }

	public function getTotalFeeBreakupNameByFeeBreakupName($fee_breakup_name) {
        $query = $this->db->query("SELECT count(*) as total FROM fee_breakup WHERE fee_breakup_name = '". $this->db->escape($fee_breakup_name)."'");
     return $query->row['total'];
	}
	
	public function getFeeBreakupNameByFeeBreakupName($fee_breakup_name) {
		$query = $this->db->query("SELECT fee_breakup_id, fee_breakup_name FROM fee_breakup WHERE fee_breakup_name = '". $this->db->escape($fee_breakup_name)."'");
		
		return $query->rows;
	}

	public function getCourseidByCode($course_code) {
		$query = $this->db->query("SELECT course_id FROM course WHERE course_code = '". $this->db->escape($course_code)."'");
		return $query->row['course_id'];
	}
	

	public function getAllFeeBreakupByClass($course_id, $session_year) {
		$query = $this->db->query("SELECT f.fee_breakup_id, f.fee_breakup_name, fs.fee, fs.frequency FROM fee_structure AS fs LEFT JOIN fee_breakup AS f ON (f.fee_breakup_id = fs.fee_breakup_id) WHERE fs.course_id = ".(int)$course_id." AND session_year ='".$session_year."'");
		
		return $query->rows;
	}

	public function calcFreqAndFee($frequency,$fee, $fee_months) {
		$temp_fee = '0';
		$take = false;
		$temp_fee = 0;
		foreach($fee_months AS $fee_month){
			$month = $fee_month;
			#$month = 5;
			
			$working_month = $month - 3;
			if($working_month < 0){
				$working_month = 12 + $working_month;
			}

			if($frequency == $working_month){
				if($month == '04')
					$take =  true;

			}elseif($frequency == '2'){
				if($working_month % 2 == 0)
					$take =  true;

			}elseif($frequency == '3'){
				if($working_month % 3 == '0' || $working_month == '1'){
					$take =  true;
				}

			}elseif($frequency == '6'){
				if($working_month % 6 == '0' || $working_month == '1'){
					$take =  true;
				}

			}elseif($frequency == '12'){
					$take =  true;
			}else $take = false;

			if($take == true){
				$temp_fee = $temp_fee + $fee;
			}
		}
		return $temp_fee;
	}
	


	/*******************	 END FEE - BREAKUP   *******************/

	/*******************	 START COTA   *******************/
	
	public function getTotalCota($filter_status,$searchString) {
		
		$sql = "SELECT COUNT(*) AS total FROM cota AS c WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= " AND fb.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND fb.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}		
		
		if(!empty($searchString)){
			$sql .= " AND (c.cota_id LIKE '%" . $this->db->escape($searchString) . "%'";
            $sql .= " OR c.cota_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR c.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);		
		return $query->row['total'];
	}


	public function getCota($start,$limit,$filter_status,$searchString) {
	
	$sql = "SELECT c.cota_id, c.cota_name, c.add_date, c.update_date FROM cota AS c WHERE 1";
	
	if($filter_status == 'inactive' ){
		$sql .= " AND fb.status=0";
	}elseif($filter_status == 'active' ){
		$sql .= " AND fb.status=1";
	}elseif($filter_status == 'all' ){
		$sql .= " ";
	}else{
		$sql .= " ";
	}		
	
	if(!empty($searchString)){
		$sql .= " AND (c.cota_id LIKE '%" . $this->db->escape($searchString) . "%'";
		$sql .= " OR c.cota_name LIKE '%" . $this->db->escape($searchString) . "%'";
		$sql .= " OR c.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
		$sql .= " OR c.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
	}
		$sql .= " ORDER BY c.add_date ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}



	public function addCota($data) {
		$this->db->query("INSERT INTO cota SET cota_name = '" . $this->db->escape($this->utf8_str_trim($data ['cota_name'])) . "', add_date=NOW()");
		if(isset($data['permissions'])){
			$cota_id = $this->db->getLastId();
			
			$permissions = $data['permissions'];
			foreach($permissions as $permission){
			   $this->db->query("INSERT INTO cota_to_fee_breakup SET cota_id = '" . $cota_id . "', fee_breakup_id = '" . $permission . "'");
			}
		}
    }

    public function editCota($data) {
	    $this->db->query("UPDATE cota SET cota_name = '" . $this->db->escape($this->utf8_str_trim($data['cota_name'])) . "', update_date = now() WHERE cota_id = '".$data['cota_id']."'");
        
        $this->db->query("DELETE FROM cota_to_fee_breakup WHERE cota_id = '". (int)$data['cota_id'] ."'");
		if(isset($data['permissions'])){
			$permissions = $data['permissions'];
			foreach($permissions as $permission){
			   $this->db->query("INSERT INTO cota_to_fee_breakup SET cota_id = '" . $data['cota_id'] . "', fee_breakup_id = '" . $permission . "'");
			}
		}
    }

	public function getTotalCotaNameByName($cota_name) {
        $query = $this->db->query("SELECT count(*) as total FROM cota WHERE cota_name = '". $this->db->escape($cota_name)."'");
	    return $query->row['total'];
	}

	public function getCotaNameByName($cota_name) {
		$query = $this->db->query("SELECT cota_id, cota_name FROM cota WHERE cota_name = '". $this->db->escape($cota_name)."'");		
		return $query->rows;
	}

	
	public function getFeeBreakupPermissionsByCotaId($cota_id) {
        $query = $this->db->query("SELECT fee_breakup_id FROM cota_to_fee_breakup WHERE cota_id = ".(int)$cota_id);
	    return $query->rows;
	}

	public function getCotaById($cota_id) {
        $query = $this->db->query("SELECT cota_id, cota_name FROM cota WHERE cota_id = ".(int)$cota_id);
	    return $query->row;
	}

	public function isExistBreakup($fee_breakup_id, $permissions){
		foreach($permissions as $permission){
			if($permission['fee_breakup_id'] == $fee_breakup_id){
				return TRUE;
			}
		}
	return FALSE;
    }

	public function getAllCota(){
		$query = $this->db->query("SELECT cota_id, cota_name FROM cota WHERE 1");
	    return $query->rows;
    }
	/*******************	 END COTA   *******************/

	/*******************	 Student Fee Report   *******************/
	    public function getTotalStudentFeeReportByDate($from_date, $to_date){
		
		$from_date = $this->dateDMYToYMDWithTime($from_date);
		$to_date = $this->dateDMYToYMDWithTime($to_date);

		$sql = "SELECT COUNT(*) AS total FROM fee WHERE add_date BETWEEN '".$this->db->escape($from_date)."' AND '".$this->db->escape($to_date)."' ORDER BY fee_id DESC";
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getStudentFeeReportByDate($start,$limit,$from_date, $to_date){
		
		$from_date = $this->dateDMYToYMDWithTime($from_date);
		$to_date = $this->dateDMYToYMDWithTime($to_date);
		
		$sql = "SELECT s.registration_no, c.course_name, f.due, f.month_of, f.month_of_2, f.fee_id, f.fee_amount, f.recipt_no, f.payable_amount, f.paid_amount, f.add_date FROM fee AS f LEFT JOIN course AS c ON (c.course_code = f.course_code) LEFT JOIN student AS s ON (f.student_id = s.student_id) WHERE f.add_date BETWEEN '".$this->db->escape($from_date)."' AND '".$this->db->escape($to_date)."' ORDER BY f.fee_id DESC LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getStudentFeeReportByDateOnly($from_date, $to_date){
		
		$from_date = $this->dateDMYToYMDWithTime($from_date);
		$to_date = $this->dateDMYToYMDWithTime($to_date);
		
		$sql = "SELECT s.registration_no, c.course_name, f.due, f.month_of, f.month_of_2, f.fee_id, f.fee_amount, f.recipt_no, f.payable_amount, f.paid_amount, f.add_date FROM fee AS f LEFT JOIN course AS c ON (c.course_code = f.course_code) LEFT JOIN student AS s ON (f.student_id = s.student_id) WHERE f.add_date BETWEEN '".$this->db->escape($from_date)."' AND '".$this->db->escape($to_date)."' ORDER BY f.fee_id DESC";
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	
	
	/*******************	 Student Fee Report   *******************/

	/*******************	 Start Session   *******************/
	public function getCurrentSession(){
		$sql = "SELECT value FROM settings WHERE setting_key = 'session_year' ";
		
		$query = $this->db->query($sql);
		return $query->row['value'];
	}
	/*******************	  End Session    *******************/

	/***********   START NUMBER TO WORD	***********************/
	public function decimal_to_words($x) {
		$x = str_replace(',','',$x);
		$pos = strpos((string)$x, ".");
		if ($pos !== false) { $decimalpart= substr($x, $pos+1, 2); $x = substr($x,0,$pos); }
		$tmp_str_rtn = $this->number_to_words ($x);
		if(!empty($decimalpart))
			$tmp_str_rtn .= ' and '  . $this->number_to_words ($decimalpart) . ' paise';
		#return   $tmp_str_rtn;
		return ucwords(strtolower("Rupees ".$tmp_str_rtn)." Only.");
	} 

	public function number_to_words ($x) {
		 
		$nwords = array(  "", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen","fourteen", "fifteen", "sixteen", "seventeen", "eightteen", "nineteen", "twenty", 30 => "thirty", 40 => "fourty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eigthy", 90 => "ninety" );
		if(!is_numeric($x)) {
			$w = '#';
		}else if(fmod($x, 1) != 0) {
			$w = '#';
		}else{
			if($x < 0) {
				$w = 'minus ';
				$x = -$x;
			}else{
				$w = '';
			}
			if($x < 21) {
				 $w .= $nwords[$x];
			 }else if($x < 100) {
				 $w .= $nwords[10 * floor($x/10)];
				 $r = fmod($x, 10);
				 if($r > 0) {
					 $w .= ' '. $nwords[$r];
				 }
			 } else if($x < 1000) {
			
				 $w .= $nwords[floor($x/100)] .' hundred';
				 $r = fmod($x, 100);
				 if($r > 0) {
					 $w .= ' '. $this->number_to_words($r);
				 }
			 } else if($x < 100000) {
				$w .= $this->number_to_words(floor($x/1000)) .' thousand';
				 $r = fmod($x, 1000);
				 if($r > 0) {
					 $w .= ' ';
					 if($r < 100)
					 {
						 $w .= ' ';
					 }
					 $w .= $this->number_to_words($r);
				 }
			 } else {
				 $w .= $this->number_to_words(floor($x/100000)) .' lacs';
				 $r = fmod($x, 100000);
				 if($r > 0) {
					 $w .= ' ';
					 if($r < 100)
					 {
						 $word .= ' ';
					 }
					 $w .= $this->number_to_words($r);
				 }
			 }
		 }
		 return $w;
	}
	/***********   END NUMBER TO WORD	***********************/

	/********************   START SUBJECT  ***********************/
	
	public function getTotalSubjects($filter_status,$searchString) {
		
		$sql = "SELECT COUNT(*) AS total FROM subject AS sbj WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= " AND sbj.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND sbj.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){
            $sql .= " AND (sbj.subject_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.subject_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getSubjects($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT sbj.subject_id, sbj.subject_code , sbj.subject_name, sbj.add_date, sbj.update_date FROM subject AS sbj WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= "AND sbj.status=0";
		}elseif($filter_status == 'active'){
			$sql .= "AND sbj.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){			
            $sql .= " AND (sbj.subject_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.subject_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR sbj.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY sbj.add_date ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}

	public function addSubject($data) {
        $query = $this->db->query("insert into subject set
		subject_code = '".$this->db->escape($data['subject_code'])."',
		subject_name = '".$this->db->escape($data['subject_name'])."',
		add_date = NOW()");
	}

	public function editSubject($data) {
        $query = $this->db->query("update subject set subject_code = '".$this->db->escape($data['subject_code'])."', subject_name = '".$this->db->escape($data['subject_name'])."', update_date = NOW() WHERE subject_id = ".(int)$data['subject_id']);
	}

	public function getSubjectById($subject_id) {
		
		$sql = "SELECT subject_id, subject_code, subject_name, add_date, update_date FROM subject WHERE subject_id = '".(int)$subject_id."'";
		$query = $this->db->query($sql);					
		return $query->row;
	}

	public function getTotalSubjectNameByName($subject_name) {
        $query = $this->db->query("SELECT count(*) AS total FROM subject WHERE subject_name = '". $this->db->escape($subject_name)."'");
		return $query->row['total'];
	}
	public function getTotalSubjectNameSubjectCode($subject_code) {
        $query = $this->db->query("SELECT count(*) AS total FROM subject WHERE subject_code = '". $this->db->escape($subject_code)."'");
		return $query->row['total'];
	}

	public function getSubjectIdBySubjectName($subject_name) {
        $query = $this->db->query("SELECT subject_id FROM subject WHERE subject_name = '". $this->db->escape($subject_name)."'");
		return $query->rows;
    }
	public function getCodeNameBySubjectCode($subject_code) {
        $query = $this->db->query("SELECT subject_id FROM subject WHERE subject_code = '". $this->db->escape($subject_code)."'");
		return $query->rows;
    }

	public function getAllSubjects() {
        $query = $this->db->query("SELECT subject_id, subject_code, subject_name FROM subject WHERE 1");
		return $query->rows;
    }

	public function GetSubjectsByCourseCode($course_code) {
        $query = $this->db->query("SELECT subject_code FROM course_to_subject WHERE course_code = '".$course_code."'");
		return $query->rows;
    }
	/*******************  END SUBJECT ***********************/


	/******************	START EXAM	***********************/
	public function getTotalExams($filter_status,$searchString) {
		
		$sql = "SELECT COUNT(*) AS total FROM exam AS ex WHERE 1";
		
		if($filter_status == 'inactive' ){
			$sql .= " AND ex.status=0";
		}elseif($filter_status == 'active' ){
			$sql .= " AND ex.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){
            $sql .= " AND (ex.exam_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.exam_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getExams($start,$limit,$filter_status,$searchString) {
		
		$sql = "SELECT ex.exam_id, ex.exam_code, ex.exam_name, ex.add_date, ex.update_date, ex.status FROM exam AS ex WHERE 1 ";
		
		if($filter_status == 'inactive' ){
			$sql .= "AND ex.status=0";
		}elseif($filter_status == 'active'){
			$sql .= "AND ex.status=1";
		}elseif($filter_status == 'all' ){
			$sql .= " ";
		}else{
			$sql .= " ";
		}
		
		if(!empty($searchString)){			
            $sql .= " AND (ex.exam_name LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.exam_code LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.add_date LIKE '%" . $this->db->escape($searchString) . "%'";
			$sql .= " OR ex.update_date LIKE '%" . $this->db->escape($searchString) . "%')";
		}

		$sql .= " ORDER BY ex.add_date ASC LIMIT " . (int)$start . "," . (int)$limit;
		$query = $this->db->query($sql);					
		return $query->rows;
	}
	
	public function addExam($data) {
        $query = $this->db->query("insert into exam set exam_name = '".$this->db->escape($data['exam_name'])."', status = '".$this->db->escape($data['status'])."', add_date = NOW()");
	}

	public function editExam($data) {
        $query = $this->db->query("update exam set
		exam_code = '".$this->db->escape($data['exam_code'])."', exam_name = '".$this->db->escape($data['exam_name'])."', status = '".$this->db->escape($data['status'])."', update_date = NOW() WHERE exam_id = ".(int)$data['exam_id']);
	}

	public function getTotalExamNameByName($exam_name) {
        $query = $this->db->query("SELECT count(*) AS total FROM exam WHERE exam_name = '". $this->db->escape($exam_name)."'");
		return $query->row['total'];
	}

	public function getTotalExamCodeByCode($exam_code) {
        $query = $this->db->query("SELECT count(*) AS total FROM exam WHERE exam_code = '". $this->db->escape($exam_code)."'");
		return $query->row['total'];
	}

	public function getExamById($exam_id) {
		
		$sql = "SELECT exam_id, exam_code, exam_name, status, add_date, update_date FROM exam WHERE exam_id = '".(int)$exam_id."'";
		$query = $this->db->query($sql);					
		return $query->row;
	}

	public function getExamByCode($exam_code) {
		
		$sql = "SELECT exam_code, exam_name FROM exam WHERE exam_code = '".$exam_code."'";
		$query = $this->db->query($sql);					
		return $query->row;
	}


	public function getExamIdByExamId($exam_id) {
        $query = $this->db->query("SELECT exam_id FROM exam WHERE exam_id = ". $this->db->escape($exam_id));
		return $query->rows;
    }

	public function getExamNameByName($exam_name) {
        $query = $this->db->query("SELECT exam_id FROM exam WHERE exam_name = '". $this->db->escape($exam_name)."'");
     return $query->rows;
    }

	public function getCodeNameByExamCode($exam_code) {
        $query = $this->db->query("SELECT exam_id FROM exam WHERE exam_code = '". $this->db->escape($exam_code)."'");
     return $query->rows;
    }


	public function getAllExams() {
	    $query = $this->db->query("SELECT exam_id, exam_code, exam_name FROM exam WHERE 1");
		return $query->rows;
    }
	
	public function GetCoursesByExamCode($exam_code, $session_year) {
        $query = $this->db->query("SELECT course_code FROM exam_to_course WHERE exam_code = '".$this->db->escape($exam_code)."' AND session_year = '".$session_year."'");
		return $query->rows;
    }

	public function setExamToCourse($courses, $exam_code) {
		
		$this->db->query("delete from exam_to_course WHERE exam_code ='".$exam_code."' AND session_year = '".$this->getCurrentSession()."'");
		
		$sql = "INSERT INTO `exam_to_course`(`exam_code`, `course_code`, `session_year`) VALUES ";
		$flag = false;
		foreach($courses AS $course){
			if($flag) $sql .= ",";
			$sql.= "('". $this->db->escape($exam_code) . "','" .$this->db->escape($course)."','".$this->getCurrentSession()."')";
			$flag = true;
		}
		$this->db->query($sql);
    }
	
	public function getCourseNameByExamCode($exam_code, $session) {

		$query = $this->db->query("SELECT course_code FROM exam_to_course WHERE exam_code = '".$exam_code."' AND session_year = '".$session."'");
		$results = $query->rows;
		$i = "0";
		$x = array();

		foreach($results AS $result){
			$query = $this->db->query("SELECT course_id, course_code, course_name FROM course WHERE course_code = '". $result['course_code']."'");
			$x[$i] = $query->row;
			$i++;
		}
		return $x;
    }

	

	public function getSubjectNameByCourseCode($course_code) {
		$query = $this->db->query("SELECT subject_code FROM course_to_subject WHERE course_code = '".$course_code."'");
		$results = $query->rows;
		$i = "0";
		$x = array();
		foreach($results AS $result){
			$query = $this->db->query("SELECT subject_code, subject_name FROM subject WHERE subject_code ='". $result['subject_code']."'");
			$x[$i] = $query->row;
			$i++;
		}
		return $x;
    }

	public function setTotalMarks($subjects, $course_code, $exam_code, $session) {
		$this->db->query("DELETE FROM total_mark_of_subject WHERE exam_code ='".$exam_code ."' AND course_code ='".$course_code."' AND session_year = '".$session."'");
		
		$sql = "INSERT INTO total_mark_of_subject(`exam_code`, `course_code`, `subject_code` , `total_mark`, `session_year`) VALUES ";
		$flag = false;
		foreach($subjects AS $key => $value){
			if($flag) $sql .= ",";
			$sql.= "('". $this->db->escape($exam_code) . "','" .$this->db->escape($course_code)."','". $this->db->escape($key) ."',".(int)$value.",'".$session."')";
			$flag = true;
		}
		$this->db->query($sql);
	}
	
	public function getTotalCourseIdtNameCourseCode($course_code) {
        $query = $this->db->query("SELECT count(*) AS total FROM course WHERE course_code = '". $this->db->escape($course_code)."'");
		return $query->row['total'];
	}

	public function getSubjectByCourseCode($course_code) {

		$query = $this->db->query("SELECT subject_code FROM course_to_subject WHERE course_code = '".$this->db->escape($course_code)."'");
		$results = $query->rows;
		$i = "0";
		$x = array();

		foreach($results AS $result){
			$query = $this->db->query("SELECT subject_id, subject_code, subject_name FROM subject WHERE subject_code = '". $result['subject_code']."'");
			$x[$i] = $query->row;
			$i++;
		}
		return $x;
    }

	public function getTotalMarkOfSubject($exam_code, $course_code, $subject_code, $session) {

		$query = $this->db->query("SELECT subject_mark_id, total_mark FROM total_mark_of_subject WHERE exam_code = '".$this->db->escape($exam_code)."' AND course_code = '".$this->db->escape($course_code)."' AND subject_code = '".$this->db->escape($subject_code)."' AND session_year = '".$this->db->escape($session)."'");
		if(isset($query -> row)) return $query -> row;
    }

	public function setStudentsObtainMarks($data, $mark_id, $session) {
		$sql = "INSERT INTO student_marks( `subject_mark_id`, `student_id`, `obtained_marks`, `session_year`) VALUES ";
		$flag = false;
		foreach($data['students'] AS $key => $value){
			if($flag) $sql .= ",";
			$sql.= "('". (int)$mark_id . "','" .(int)$key."','". (int)$value ."','".$session."')";
			$flag = true;
		}
		$this->db->query($sql);
    }
	
	public function checkMarkEntryDone($exam_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM student_marks WHERE subject_mark_id = '".$this->db->escape($exam_id)."'");
		return $query -> row['total'];
    }

	public function getMarkIdByExamClass($exam_code, $course_code, $session) {

		$query = $this->db->query("SELECT subject_mark_id, course_code FROM total_mark_of_subject WHERE exam_code = '".$this->db->escape($exam_code)."' AND course_code = '".$this->db->escape($course_code)."' AND session_year = '".$this->db->escape($session)."'");
		if(isset($query -> rows)) return $query -> rows;

    }
	public function getExamByExamCode($exam_code, $session) {

		$query = $this->db->query("SELECT subject_mark_id, subject_code, course_code FROM total_mark_of_subject WHERE exam_code = '".$this->db->escape($exam_code)."' AND session_year = '".$this->db->escape($session)."'");
		if(isset($query -> rows)) return $query -> rows;
    }

	public function getObtainMark($exam_id, $student_id, $session) {

		$query = $this->db->query("SELECT obtained_marks FROM student_marks WHERE subject_mark_id = '".$this->db->escape($exam_id)."' AND student_id = '".$this->db->escape($student_id)."' AND session_year = '".$this->db->escape($session)."'");
		if(isset($query -> row)) return $query -> row;
    }

	public function getSubjectNameByCode($subject_code) {

		$query = $this->db->query("SELECT subject_name FROM subject WHERE subject_code = '".$this->db->escape($subject_code)."'");
		if(isset($query -> row)) return $query -> row;
    }

	/******************	 END EXAM	***********************/


	//**************************************************************************************************************//

	
	/*helper*/

	public function dateYMDToDMY($date){
	
		if( $date == '0000-00-00'){
			return $date = '00-00-0000';
		}elseif(!isset($date)){
			return $date = '00-00-0000';
		}else{
			return $date = date('d-m-Y', strtotime($date));
		}
	}

	public function dateDMYToYMD($date){	
		
		if( $date == '00-00-0000'){
			return $date = '0000-00-00';
		}elseif(!isset($date)){
			return $date = '0000-00-00';
		}else{
			return $date = date('Y-m-d', strtotime($date));
		}
	}

	public function dateYMDToDMYWithTime($date){
	
		if( $date == '0000-00-00 00:00:00'){
			return $date = '00-00-0000';
		}elseif(!isset($date)){
			return $date = '00-00-0000';
		}else{
			return $date = date('d-m-Y', strtotime($date));
		}
	}

	public function dateDMYToYMDWithTime($date){	
		
		if( $date == '00-00-0000'){
			return $date = '0000-00-00 0000-00-00 00:00:00';
		}elseif(!isset($date)){
			return $date = '0000-00-00 0000-00-00 00:00:00';
		}else{
			return $date = date('Y-m-d', strtotime($date));
		}
	}

	public function dateDMYTimeToYMDTime($date){	
	
		if( $date == '0000-00-00 00:00:00'){
			return $date = '00-00-0000, 00:00';
		}elseif(!isset($date)) {
			return $date = '00-00-0000, 00:00';
		}else{
			return $date = date('d-m-Y, h:i A', strtotime($date));
		}
		
	}	

	public function chkDesiNum($x){		
		if(!preg_match("/^[0-9.]*$/", $x)){
			return false;
		}
		return true;
	}

	public function decode_entities($text) {
		$text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
		#$text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text); #decimal notation		
		#$text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);  #hex notation
		$text= preg_replace('/&#(\d+);/m',"chr(\\1)",$text); #decimal notation
		$text= preg_replace('/&#x([a-f0-9]+);/mi',"chr(0x\\1)",$text);  #hex notation
		return $text;
	}

	function utf8_urldecode($str) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		return html_entity_decode($str,null,'UTF-8');		
	}

	public function utf8_br2nl($string) {
		$string= preg_replace('/<br\s*\/>/is',"\n",$string);
		$string= preg_replace('/<br>/is',"\n",$string);
		
		return $string;	
	}

	public function utf8_strlen($string) {
		$string=trim($string);
		return strlen(utf8_decode($string));
	}

	public function utf8_str_trim($string) {
		$string=trim($string);
		return $string;
	}

	public function utf8_substr($string, $offset, $length = null) {
		// generates E_NOTICE
		// for PHP4 objects, but not PHP5 objects
		$string = (string)$string;
		$offset = (int)$offset;
		
		if (!is_null($length)) {
			$length = (int)$length;
		}
		
		// handle trivial cases
		if ($length === 0) {
			return '';
		}
		
		if ($offset < 0 && $length < 0 && $length < $offset) {
			return '';
		}
		
		// normalise negative offsets (we could use a tail
		// anchored pattern, but they are horribly slow!)
		if ($offset < 0) {
			$strlen = strlen(utf8_decode($string));
			$offset = $strlen + $offset;
			
			if ($offset < 0) {
				$offset = 0;
			}
		}
		
		$Op = '';
		$Lp = '';
		
		// establish a pattern for offset, a
		// non-captured group equal in length to offset
		if ($offset > 0) {
			$Ox = (int)($offset / 65535);
			$Oy = $offset%65535;
			
			if ($Ox) {
				$Op = '(?:.{65535}){' . $Ox . '}';
			}
			
			$Op = '^(?:' . $Op . '.{' . $Oy . '})';
		} else {
			$Op = '^';
		}
		
		// establish a pattern for length
		if (is_null($length)) {
			$Lp = '(.*)$';
		} else {
			if (!isset($strlen)) {
				$strlen = strlen(utf8_decode($string));
			}
			
			// another trivial case
			if ($offset > $strlen) {
				return '';
			}
			
			if ($length > 0) {
				$length = min($strlen - $offset, $length);
				
				$Lx = (int)($length / 65535);
				$Ly = $length % 65535;
				
				// negative length requires a captured group
				// of length characters
				if ($Lx) {
					$Lp = '(?:.{65535}){' . $Lx . '}';
				}
				
				$Lp = '(' . $Lp . '.{' . $Ly . '})';
			} elseif ($length < 0) {
				if ($length < ($offset - $strlen)) {
					return '';
				}
				
				$Lx = (int)((-$length) / 65535);
				$Ly = (-$length)%65535;
				
				// negative length requires ... capture everything
				// except a group of  -length characters
				// anchored at the tail-end of the string
				if ($Lx) {
					$Lp = '(?:.{65535}){' . $Lx . '}';
				}
				
				$Lp = '(.*)(?:' . $Lp . '.{' . $Ly . '})$';
			}
		}
		
		if (!preg_match( '#' . $Op . $Lp . '#us', $string, $match)) {
			return '';
		}
		
		return $match[1];
		
	}



	public function utf8_strtolower($string) {
		static $UTF8_UPPER_TO_LOWER = NULL;
		
		if (is_null($UTF8_UPPER_TO_LOWER)) {
			$UTF8_UPPER_TO_LOWER = array(
				0x0041 => 0x0061, 
				0x03A6 => 0x03C6, 
				0x0162 => 0x0163, 
				0x00C5 => 0x00E5, 
				0x0042 => 0x0062,
				0x0139 => 0x013A, 
				0x00C1 => 0x00E1, 
				0x0141 => 0x0142, 
				0x038E => 0x03CD, 
				0x0100 => 0x0101,
				0x0490 => 0x0491, 
				0x0394 => 0x03B4, 
				0x015A => 0x015B, 
				0x0044 => 0x0064, 
				0x0393 => 0x03B3,
				0x00D4 => 0x00F4, 
				0x042A => 0x044A, 
				0x0419 => 0x0439, 
				0x0112 => 0x0113, 
				0x041C => 0x043C,
				0x015E => 0x015F, 
				0x0143 => 0x0144, 
				0x00CE => 0x00EE, 
				0x040E => 0x045E, 
				0x042F => 0x044F,
				0x039A => 0x03BA, 
				0x0154 => 0x0155, 
				0x0049 => 0x0069, 
				0x0053 => 0x0073, 
				0x1E1E => 0x1E1F,
				0x0134 => 0x0135, 
				0x0427 => 0x0447, 
				0x03A0 => 0x03C0, 
				0x0418 => 0x0438, 
				0x00D3 => 0x00F3,
				0x0420 => 0x0440, 
				0x0404 => 0x0454, 
				0x0415 => 0x0435, 
				0x0429 => 0x0449, 
				0x014A => 0x014B,
				0x0411 => 0x0431, 
				0x0409 => 0x0459, 
				0x1E02 => 0x1E03, 
				0x00D6 => 0x00F6, 
				0x00D9 => 0x00F9,
				0x004E => 0x006E, 
				0x0401 => 0x0451, 
				0x03A4 => 0x03C4, 
				0x0423 => 0x0443, 
				0x015C => 0x015D,
				0x0403 => 0x0453, 
				0x03A8 => 0x03C8, 
				0x0158 => 0x0159, 
				0x0047 => 0x0067, 
				0x00C4 => 0x00E4,
				0x0386 => 0x03AC, 
				0x0389 => 0x03AE, 
				0x0166 => 0x0167, 
				0x039E => 0x03BE, 
				0x0164 => 0x0165,
				0x0116 => 0x0117, 
				0x0108 => 0x0109, 
				0x0056 => 0x0076, 
				0x00DE => 0x00FE, 
				0x0156 => 0x0157,
				0x00DA => 0x00FA, 
				0x1E60 => 0x1E61, 
				0x1E82 => 0x1E83, 
				0x00C2 => 0x00E2, 
				0x0118 => 0x0119,
				0x0145 => 0x0146, 
				0x0050 => 0x0070, 
				0x0150 => 0x0151, 
				0x042E => 0x044E, 
				0x0128 => 0x0129,
				0x03A7 => 0x03C7, 
				0x013D => 0x013E, 
				0x0422 => 0x0442, 
				0x005A => 0x007A, 
				0x0428 => 0x0448,
				0x03A1 => 0x03C1, 
				0x1E80 => 0x1E81, 
				0x016C => 0x016D, 
				0x00D5 => 0x00F5, 
				0x0055 => 0x0075,
				0x0176 => 0x0177, 
				0x00DC => 0x00FC, 
				0x1E56 => 0x1E57, 
				0x03A3 => 0x03C3, 
				0x041A => 0x043A,
				0x004D => 0x006D, 
				0x016A => 0x016B, 
				0x0170 => 0x0171, 
				0x0424 => 0x0444, 
				0x00CC => 0x00EC,
				0x0168 => 0x0169, 
				0x039F => 0x03BF, 
				0x004B => 0x006B, 
				0x00D2 => 0x00F2, 
				0x00C0 => 0x00E0,
				0x0414 => 0x0434, 
				0x03A9 => 0x03C9, 
				0x1E6A => 0x1E6B, 
				0x00C3 => 0x00E3, 
				0x042D => 0x044D,
				0x0416 => 0x0436, 
				0x01A0 => 0x01A1, 
				0x010C => 0x010D, 
				0x011C => 0x011D, 
				0x00D0 => 0x00F0,
				0x013B => 0x013C, 
				0x040F => 0x045F, 
				0x040A => 0x045A, 
				0x00C8 => 0x00E8, 
				0x03A5 => 0x03C5,
				0x0046 => 0x0066, 
				0x00DD => 0x00FD, 
				0x0043 => 0x0063, 
				0x021A => 0x021B, 
				0x00CA => 0x00EA,
				0x0399 => 0x03B9, 
				0x0179 => 0x017A, 
				0x00CF => 0x00EF, 
				0x01AF => 0x01B0, 
				0x0045 => 0x0065,
				0x039B => 0x03BB, 
				0x0398 => 0x03B8, 
				0x039C => 0x03BC, 
				0x040C => 0x045C, 
				0x041F => 0x043F,
				0x042C => 0x044C, 
				0x00DE => 0x00FE, 
				0x00D0 => 0x00F0, 
				0x1EF2 => 0x1EF3, 
				0x0048 => 0x0068,
				0x00CB => 0x00EB, 
				0x0110 => 0x0111, 
				0x0413 => 0x0433, 
				0x012E => 0x012F, 
				0x00C6 => 0x00E6,
				0x0058 => 0x0078, 
				0x0160 => 0x0161, 
				0x016E => 0x016F, 
				0x0391 => 0x03B1, 
				0x0407 => 0x0457,
				0x0172 => 0x0173, 
				0x0178 => 0x00FF, 
				0x004F => 0x006F, 
				0x041B => 0x043B, 
				0x0395 => 0x03B5,
				0x0425 => 0x0445, 
				0x0120 => 0x0121, 
				0x017D => 0x017E, 
				0x017B => 0x017C, 
				0x0396 => 0x03B6,
				0x0392 => 0x03B2, 
				0x0388 => 0x03AD, 
				0x1E84 => 0x1E85, 
				0x0174 => 0x0175, 
				0x0051 => 0x0071,
				0x0417 => 0x0437, 
				0x1E0A => 0x1E0B, 
				0x0147 => 0x0148, 
				0x0104 => 0x0105, 
				0x0408 => 0x0458,
				0x014C => 0x014D, 
				0x00CD => 0x00ED, 
				0x0059 => 0x0079, 
				0x010A => 0x010B, 
				0x038F => 0x03CE,
				0x0052 => 0x0072, 
				0x0410 => 0x0430, 
				0x0405 => 0x0455, 
				0x0402 => 0x0452, 
				0x0126 => 0x0127,
				0x0136 => 0x0137, 
				0x012A => 0x012B, 
				0x038A => 0x03AF, 
				0x042B => 0x044B, 
				0x004C => 0x006C,
				0x0397 => 0x03B7, 
				0x0124 => 0x0125, 
				0x0218 => 0x0219, 
				0x00DB => 0x00FB, 
				0x011E => 0x011F,
				0x041E => 0x043E, 
				0x1E40 => 0x1E41, 
				0x039D => 0x03BD, 
				0x0106 => 0x0107, 
				0x03AB => 0x03CB,
				0x0426 => 0x0446, 
				0x00DE => 0x00FE, 
				0x00C7 => 0x00E7, 
				0x03AA => 0x03CA, 
				0x0421 => 0x0441,
				0x0412 => 0x0432, 
				0x010E => 0x010F, 
				0x00D8 => 0x00F8, 
				0x0057 => 0x0077, 
				0x011A => 0x011B,
				0x0054 => 0x0074, 
				0x004A => 0x006A, 
				0x040B => 0x045B, 
				0x0406 => 0x0456, 
				0x0102 => 0x0103, 
				0x039B => 0x03BB, 
				0x00D1 => 0x00F1, 
				0x041D => 0x043D, 
				0x038C => 0x03CC, 
				0x00C9 => 0x00E9, 
				0x00D0 => 0x00F0, 
				0x0407 => 0x0457, 
				0x0122 => 0x0123
			);
		}	
		$unicode = $this->utf8_to_unicode($string);	
		if (!$unicode) {
			return false;
		}	
		$count = count($unicode);	
		for ($i = 0; $i < $count; $i++){
			if (isset($UTF8_UPPER_TO_LOWER[$unicode[$i]]) ) {
				$unicode[$i] = $UTF8_UPPER_TO_LOWER[$unicode[$i]];
			}
		}	
		return $this->utf8_from_unicode($unicode);
	}
	
	function utf8_to_unicode($str) {
	$mState = 0;     // cached expected number of octets after the current octet
					 // until the beginning of the next UTF8 character sequence
	$mUcs4  = 0;     // cached Unicode character
	$mBytes = 1;     // cached expected number of octets in the current sequence
	
	$out = array();
	
	$len = strlen($str);
	
	for($i = 0; $i < $len; $i++) {
		$in = ord($str{$i});
		
		if ($mState == 0) {
			
			// When mState is zero we expect either a US-ASCII character or a
			// multi-octet sequence.
			if (0 == (0x80 & ($in))) {
				// US-ASCII, pass straight through.
				$out[] = $in;
				$mBytes = 1;
				
			} elseif (0xC0 == (0xE0 & ($in))) {
				// First octet of 2 octet sequence
				$mUcs4 = ($in);
				$mUcs4 = ($mUcs4 & 0x1F) << 6;
				$mState = 1;
				$mBytes = 2;
				
			} elseif (0xE0 == (0xF0 & ($in))) {
				// First octet of 3 octet sequence
				$mUcs4 = ($in);
				$mUcs4 = ($mUcs4 & 0x0F) << 12;
				$mState = 2;
				$mBytes = 3;
				
			} else if (0xF0 == (0xF8 & ($in))) {
				// First octet of 4 octet sequence
				$mUcs4 = ($in);
				$mUcs4 = ($mUcs4 & 0x07) << 18;
				$mState = 3;
				$mBytes = 4;
				
			} else if (0xF8 == (0xFC & ($in))) {
				/* First octet of 5 octet sequence.
				*
				* This is illegal because the encoded codepoint must be either
				* (a) not the shortest form or
				* (b) outside the Unicode range of 0-0x10FFFF.
				* Rather than trying to resynchronize, we will carry on until the end
				* of the sequence and let the later error handling code catch it.
				*/
				$mUcs4 = ($in);
				$mUcs4 = ($mUcs4 & 0x03) << 24;
				$mState = 4;
				$mBytes = 5;
				
			} else if (0xFC == (0xFE & ($in))) {
				// First octet of 6 octet sequence, see comments for 5 octet sequence.
				$mUcs4 = ($in);
				$mUcs4 = ($mUcs4 & 1) << 30;
				$mState = 5;
				$mBytes = 6;
				
			} else {
				/* Current octet is neither in the US-ASCII range nor a legal first
				 * octet of a multi-octet sequence.
				 */
				trigger_error('utf8_to_unicode: Illegal sequence identifier ' . 'in UTF-8 at byte ' . $i, E_USER_WARNING);
				
				return FALSE;
			}
		
		} else {
			
			// When mState is non-zero, we expect a continuation of the multi-octet
			// sequence
			if (0x80 == (0xC0 & ($in))) {
				
				// Legal continuation.
				$shift = ($mState - 1) * 6;
				$tmp = $in;
				$tmp = ($tmp & 0x0000003F) << $shift;
				$mUcs4 |= $tmp;
			
				/**
				* End of the multi-octet sequence. mUcs4 now contains the final
				* Unicode codepoint to be output
				*/
				if (0 == --$mState) {
					
					/*
					* Check for illegal sequences and codepoints.
					*/
					// From Unicode 3.1, non-shortest form is illegal
					if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
						((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
						((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
						(4 < $mBytes) ||
						// From Unicode 3.2, surrogate characters are illegal
						(($mUcs4 & 0xFFFFF800) == 0xD800) ||
						// Codepoints outside the Unicode range are illegal
						($mUcs4 > 0x10FFFF)) {
						
						trigger_error('utf8_to_unicode: Illegal sequence or codepoint in UTF-8 at byte ' . $i, E_USER_WARNING);
						
						return false;
						
					}
					
					if (0xFEFF != $mUcs4) {
						// BOM is legal but we don't want to output it
						$out[] = $mUcs4;
					}
					
					//initialize UTF8 cache
					$mState = 0;
					$mUcs4  = 0;
					$mBytes = 1;
				}
			
			} else {
				/**
				*((0xC0 & (*in) != 0x80) && (mState != 0))
				* Incomplete multi-octet sequence.
				*/
				trigger_error('utf8_to_unicode: Incomplete multi-octet sequence in UTF-8 at byte ' . $i, E_USER_WARNING);
				
				return false;
			}
		}
	}
	
	return $out;
}

function utf8_from_unicode($data) {
	ob_start();
	
	foreach (array_keys($data) as $key) {
		if (($data[$key] >= 0) && ($data[$key] <= 0x007f)) {
			echo chr($data[$key]);
		} elseif ($data[$key] <= 0x07ff) {
			echo chr(0xc0 | ($data[$key] >> 6));
			echo chr(0x80 | ($data[$key] & 0x003f));
		} elseif ($data[$key] == 0xFEFF) {
		// nop -- zap the BOM
		
		# Test for illegal surrogates
		} elseif ($data[$key] >= 0xD800 && $data[$key] <= 0xDFFF) {
			trigger_error('utf8_from_unicode: Illegal surrogate at index: ' . $key . ', value: ' . $data[$key], E_USER_WARNING);
			
			return false;
		} elseif ($data[$key] <= 0xffff) {
			echo chr(0xe0 | ($data[$key] >> 12));
			echo chr(0x80 | (($data[$key] >> 6) & 0x003f));
			echo chr(0x80 | ($data[$key] & 0x003f));
		} elseif ($data[$key] <= 0x10ffff) {
			echo chr(0xf0 | ($data[$key] >> 18));
			echo chr(0x80 | (($data[$key] >> 12) & 0x3f));
			echo chr(0x80 | (($data[$key] >> 6) & 0x3f));
			echo chr(0x80 | ($data[$key] & 0x3f));
		} else {
			trigger_error('utf8_from_unicode: Codepoint out of Unicode range at index: ' . $key . ', value: ' . $data[$key], E_USER_WARNING);
			
			return false;
		}
	}
	
	$result = ob_get_contents();
	
	ob_end_clean();
	
	return $result;
}/*End helper*/



}/*End Class*/
?>