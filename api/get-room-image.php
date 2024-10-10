<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$roomId = $_REQUEST["roomid"];
	$p = new clsapi();
	
	$p->roomImage("SELECT * FROM rooms_images left join rooms on rooms_images.room_id = rooms.id where room_id=$roomId order by sr_no ASC");
	
?>