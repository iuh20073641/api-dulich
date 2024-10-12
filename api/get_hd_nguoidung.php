<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Xử lý yêu cầu OPTIONS trước khi xử lý các yêu cầu khác
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức HTTP không hợp lệ.']);
    exit();
}

include("../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy user_id từ dữ liệu POST
$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số user_id.']);
    exit();
}

// Thực hiện truy vấn SQL
$query = "SELECT bo.*, bd.* FROM `booking_order` bo
    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
    WHERE ((bo.booking_status = 'booker') 
    OR (bo.booking_status = 'cancelled')
    OR (bo.booking_status = 'payment failed'))
    AND (bo.user_id=?)
    ORDER BY bo.booking_id DESC";

$result = $p->execute_query($query, [$user_id], 'i');

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lấy thông tin đơn đặt phòng.']);
}
