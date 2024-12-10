<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../../myclass/clsapi.php");
	$p = new clsapi();
	
	$p->allRoomRating("select rating_review.*, user_cred.name as user_name , user_cred.profile, rooms.name AS room_name
				from rating_review
				left join user_cred on rating_review.user_id = user_cred.id 
				LEFT JOIN rooms ON rating_review.room_id = rooms.id 
				order by sr_no ASC");
	
?>