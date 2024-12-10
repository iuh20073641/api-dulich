<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../../myclass/clsapi.php");
    $id_schedule = $_REQUEST[('id_schedule')];
	$p = new clsapi();
	
	$p->tourSchedule("select * from tour_schedule where id=$id_schedule");
	
?>