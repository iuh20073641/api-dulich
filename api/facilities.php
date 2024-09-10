<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$roomId = $_REQUEST["roomid"];
	$p = new clsapi();
	
	$p->facilities("SELECT f.id, f.name  FROM `facilities` f 
            INNER JOIN `rooms_facilities` rfac ON f.id = rfac.facilities_id 
             WHERE rfac.room_id = $roomId");
	
?>