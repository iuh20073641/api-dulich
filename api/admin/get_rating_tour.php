<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../../myclass/clsapi.php");
	$p = new clsapi();
	
	$p->allTourRating("select rating_review_tour.*, user_cred.name as user_name , user_cred.profile, tours.name AS tour_name
				from rating_review_tour 
				left join user_cred on rating_review_tour.user_id = user_cred.id 
				LEFT JOIN tours ON rating_review_tour.tour_id = tours.id 
				order by sr_no ASC");
	
?>