<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include("../myclass/clsapi.php");

$p = new clsapi();

// Kiểm tra xem có tham số id hay không
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $p->News("SELECT * FROM news WHERE id = $id");
} else {
    $p->News("SELECT * FROM news ORDER BY id ASC");
}