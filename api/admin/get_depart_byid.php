<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../../myclass/clsapi.php");

	$departId = $_REQUEST["departid"];
	$p = new clsapi();
	
	$p->tourDepart("SELECT * FROM departure_time where id=$departId");
	
?>