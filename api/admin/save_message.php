<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

// Tạo kết nối
$p = new clsapi();

// Lấy dữ liệu từ yêu cầu POST
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (!isset($data['id'], $data['userId'], $data['userName'], $data['text'], $data['room'], $data['timestamp'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit();
}

$id = $data['id'];
$userId = $data['userId'];
$userName = $data['userName'];
$text = $data['text'];
$room = $data['room'];
$timestamp = $data['timestamp'];

// Chuẩn bị và thực thi câu lệnh SQL
$sql = "INSERT INTO messages (id, userId, userName, text, room, timestamp) VALUES (?, ?, ?, ?, ?, ?)";
$values = [$id, $userId, $userName, $text, $room, $timestamp];
$types = "iissss";

$result = $p->execute_query($sql, $values, $types);

if ($result) {
    echo json_encode(["message" => "Message saved successfully"]);
} else {
    echo json_encode(["error" => "Failed to save message"]);
}
    