<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");
    $tourId = $_REQUEST[('tourid')];
	$p = new clsapi();
	
	$p->tourRating("select * from rating_review_tour left join user_cred on rating_review_tour.user_id = user_cred.id where tour_id=$tourId order by sr_no ASC");
	
?>