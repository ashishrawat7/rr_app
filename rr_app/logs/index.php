<?php

//Detect special conditions devices
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

echo "iPod=".$iPod."<br>";
echo "iPhone=".$iPhone."<br>";
echo "iPad=".$iPad."<br>";
echo "Android=".$Android."<br>";
echo "webOS=".$webOS."<br>";

//do something with this information
if( $iPod || $iPhone ){
    echo "iPod";
}else if($iPad){
    echo "iPad".$_SERVER['HTTP_USER_AGENT'];
}else if($Android){
    echo "Android".$_SERVER['HTTP_USER_AGENT'];
}else if($webOS){
    echo "webOS";
}else{
	echo $_SERVER['HTTP_USER_AGENT'];
}


echo "</br>";
echo "</br>";
echo "</br>";

echo php_uname('s');/* Operating system name */ 
echo "<br />"; 
echo php_uname('n');/* Host name */ 
echo "<br />"; 
echo php_uname('r');/* Release name */ 
echo "<br />"; 
echo php_uname('v');/* Version information */ 
echo "<br />"; 
echo php_uname('m');/* Machine type */ 
echo "<br />"; 
echo PHP_OS;/* constant will contain the operating system PHP was built on */ 


echo "</br>";
echo "</br>";
echo "</br>";

?> 