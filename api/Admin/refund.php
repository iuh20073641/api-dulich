<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Xử lý yêu cầu OPTIONS trước khi xử lý các yêu cầu khác
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Phương thức HTTP không hợp lệ.']);
    exit();
}

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$booking_id = $data['booking_id'] ?? null;

// Kiểm tra tham số
if (!$booking_id) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số booking_id.']);
    exit();
}

// Kiểm tra đơn đặt phòng có tồn tại không
$sql_check = "SELECT * FROM `booking_order` WHERE `booking_id` = ?";
$booking_exists = $p->execute_query($sql_check, [$booking_id]);

if (!$booking_exists) {
    http_response_code(404); // Not found
    echo json_encode(['status' => 'error', 'message' => 'Đơn đặt phòng không tồn tại.']);
    exit();
}

// Xử lý yêu cầu hoàn tiền
if (isset($data['refund_booking'])) {
    $query = "UPDATE `booking_order` SET `refund` = ? WHERE `booking_id` = ?";
    $values = [1, $booking_id];
    $res = $p->execute_query($query, $values, 'ii');

    if ($res) {
        http_response_code(200); // Success
        echo json_encode(['status' => 'success', 'message' => 'Đơn đặt phòng đã được hoàn tiền thành công.']);
    } else {
        http_response_code(500); // Server error
        echo json_encode(['status' => 'error', 'message' => 'Hoàn tiền đơn đặt phòng không thành công.']);
    }
    exit();
}
