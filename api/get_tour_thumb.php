<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$tourId = $_REQUEST["tourid"];
	$p = new clsapi();
	
	$p->tourImage("SELECT * FROM tours_images where tour_id=$tourId and thumb = 1 order by sr_no ASC");
	
?>