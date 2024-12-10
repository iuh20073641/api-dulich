<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

    $depart_id = $_REQUEST["depart_id"];
	$p = new clsapi();
	
	$p->checkBookingTour("SELECT bo.*, bd.* FROM `booking_order_tour` bo
        INNER JOIN `booking_detail_tour` bd ON bo.booking_id = bd.booking_id
        WHERE bo.departure_id = $depart_id AND bo.refund IS NULL");
    
?>