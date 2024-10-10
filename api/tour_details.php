<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");
    $id_tour = $_REQUEST[('idtour')];
	$p = new clsapi();
	
	$p->xemTours("select * from tours where id=$id_tour order by id ASC");
	
?>