<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

    $location = $_REQUEST[('destination')];
    $date = $_REQUEST[('date')];
    $style = $_REQUEST[('style')];
    
    $priceRange = json_decode($_REQUEST[('priceRange')], true);
    $minPrice = $priceRange['min'];
    $maxPrice = $priceRange['max'];

	$p = new clsapi();
	
	$p->seachTours("SELECT * FROM tours t
              LEFT JOIN departure_time nkh ON t.id = nkh.id_tour
              WHERE t.departurelocation LIKE  '%$location%'
              AND nkh.day_depar = '$date'
              AND (t.price - (t.price/100*t.discount)) BETWEEN $minPrice AND $maxPrice
              AND t.style = '$style';");
	
?>