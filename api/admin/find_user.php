<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

	$info_user = $_REQUEST["info_user"];
    
	$p = new clsapi();
	
	$p->checkClient("SELECT * FROM `user_cred` WHERE `email`='$info_user' OR `phonenum`='$info_user' LIMIT 1");
	
?>