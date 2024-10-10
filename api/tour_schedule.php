<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");
    $id_tour = $_REQUEST[('idtour')];
	$p = new clsapi();
	
	$p->tourSchedule("select * from tour_schedule where id_tour=$id_tour order by date ASC");
	
?>