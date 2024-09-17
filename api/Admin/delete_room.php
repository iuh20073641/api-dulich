<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu DELETE
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$room_id = $data['room_id'] ?? null;

// Kiểm tra tham số
if (!$room_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số phòng ID.']);
    exit();
}

// Xóa các facilities liên kết với phòng
$sql_delete_facilities = "DELETE FROM `rooms_facilities` WHERE `room_id` = ?";
$p->execute_query($sql_delete_facilities, [$room_id]);

// Xóa các features liên kết với phòng
$sql_delete_features = "DELETE FROM `rooms_features` WHERE `room_id` = ?";
$p->execute_query($sql_delete_features, [$room_id]);

// Xóa phòng
$sql_delete_room = "DELETE FROM `rooms` WHERE `id` = ?";
$result = $p->execute_query($sql_delete_room, [$room_id]);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Phòng đã được xóa thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Xóa phòng không thành công.']);
}
