<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$email = $_REQUEST["email"];
    $phone = $_REQUEST["phonenum"];
	$p = new clsapi();
	
	$p->checkClient("SELECT id, email, phonenum FROM `user_cred` WHERE `email`='$email' OR `phonenum`='$phone' LIMIT 1");
	
?>