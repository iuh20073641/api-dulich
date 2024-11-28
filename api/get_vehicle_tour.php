<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$id_depart = $_REQUEST[("id_depart")];
	$p = new clsapi();
	
	$p->vahicleDepartTour("SELECT v.*, dt.day_depar
                            FROM `vehicle` v
                            INNER JOIN `departure_time` dt ON v.id_depart = dt.id
                            WHERE v.id_depart = '$id_depart'");
	
?>