<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

// Tạo kết nối
$p = new clsapi();

// Lấy dữ liệu từ yêu cầu GET
$room = $_GET['room'] ?? '';

// Kiểm tra dữ liệu đầu vào
if (empty($room)) {
    echo json_encode(["error" => "Missing required field: room"]);
    exit();
}

// Chuẩn bị và thực thi câu lệnh SQL
$sql = "SELECT * FROM messages WHERE room = ?";
$values = [$room];
$types = "s";

$messages = $p->execute_query($sql, $values, $types);

if ($messages !== false) {
    echo json_encode($messages);
} else {
    echo json_encode(["error" => "Failed to retrieve messages"]);
}
