<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$roomId = $_REQUEST["roomid"];
	$p = new clsapi();
	
	$p->features("SELECT f.name,sr_no FROM `features` f 
                INNER JOIN `rooms_features` rfea ON f.id = rfea.features_id 
                WHERE rfea.room_id = $roomId");
	
?>