<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

    $now_date = date("Y-m-d");
    $check_in = $_REQUEST["check_in"];
    $check_out = $_REQUEST["check_out"];
	$p = new clsapi();
	
	$p->xemRoom("SELECT r.*
        FROM rooms r
        WHERE r.id NOT IN (
            SELECT DISTINCT b.room_id
            FROM booking_order b
            WHERE (('$check_in' BETWEEN check_in AND check_out) OR 
            ('$check_out' BETWEEN check_in AND check_out) OR 
            (check_in BETWEEN '$check_in' AND '$check_out') OR 
            (check_out BETWEEN '$check_in' AND '$check_out'))
        );");
    
?>