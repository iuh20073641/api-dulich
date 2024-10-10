<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");
    $roomId = $_REQUEST[('roomid')];
	$p = new clsapi();
	
	$p->roomRating("select * from rating_review left join user_cred on rating_review.user_id = user_cred.id where room_id=$roomId order by sr_no ASC");
	
?>