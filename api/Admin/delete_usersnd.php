<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu DELETE
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$user_id = $data['user_id'] ?? null;

// Kiểm tra tham số
if (!$user_id) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số user ID.']);
    exit();
}

// Kiểm tra người dùng có tồn tại không
$sql_check = "SELECT * FROM `user_cred` WHERE `id` = ?";
$user_exists = $p->execute_query($sql_check, [$user_id]);

if (!$user_exists) {
    http_response_code(404); // Not found
    echo json_encode(['status' => 'error', 'message' => 'Người dùng không tồn tại.']);
    exit();
}

// Xóa người dùng
$sql_delete_user = "DELETE FROM `user_cred` WHERE `id` = ?";
$result = $p->execute_query($sql_delete_user, [$user_id]);

if ($result) {
    http_response_code(200); // Success
    echo json_encode(['status' => 'success', 'message' => 'Người dùng đã được xóa thành công.']);
} else {
    http_response_code(500); // Server error
    echo json_encode(['status' => 'error', 'message' => 'Xóa người dùng không thành công.']);
}
