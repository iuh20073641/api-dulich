<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Xử lý yêu cầu OPTIONS trước khi xử lý các yêu cầu khác
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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
$article_id = $data['article_id'] ?? null;

// Kiểm tra tham số
if (!$article_id) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số employee ID.']);
    exit();
}

// Kiểm tra nhân viên có tồn tại không
$sql_check = "SELECT * FROM `news` WHERE `id` = ?";
$user_exists = $p->execute_query($sql_check, [$article_id]);

if (!$user_exists) {
    http_response_code(404); // Not found
    echo json_encode(['status' => 'error', 'message' => 'Nhân viên không tồn tại.']);
    exit();
}

// Xóa nhân viên
$sql_delete_user = "DELETE FROM `news` WHERE `id` = ?";
$result = $p->execute_query($sql_delete_user, [$article_id]);

if ($result) {
    http_response_code(200); // Success
    echo json_encode(['status' => 'success', 'message' => 'Tin tức đã được xóa thành công.']);
} else {
    http_response_code(500); // Server error
    echo json_encode(['status' => 'error', 'message' => 'Xóa tin tức không thành công.']);
}