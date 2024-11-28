<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

    $id_room = $_REQUEST["room_id"];
    $now_date = date("Y-m-d");
    $check_in = $_REQUEST["check_in"];
    $check_out = $_REQUEST["check_out"];
	$p = new clsapi();
	
	$p->checkBookingRoom("SELECT * 
        FROM `booking_order` 
        WHERE room_id = $id_room 
        AND (
            ('$check_in' BETWEEN check_in AND check_out) OR 
            ('$check_out' BETWEEN check_in AND check_out) OR 
            (check_in BETWEEN '$check_in' AND '$check_out') OR 
            (check_out BETWEEN '$check_in' AND '$check_out')
        )");
	
?>