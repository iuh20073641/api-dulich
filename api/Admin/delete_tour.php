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
$tour_id = $data['tour_id'] ?? null;

// Kiểm tra tham số
if (!$tour_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số phòng ID.']);
    exit();
}
// Xóa phòng
$sql_delete_tour = "DELETE FROM `tour1` WHERE `id` = ?";
$result = $p->execute_query($sql_delete_tour, [$tour_id]);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Tour đã được xóa thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tour phòng không thành công.']);
}
