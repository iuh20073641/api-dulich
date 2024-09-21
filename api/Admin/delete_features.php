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
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$feature_id = $data['feature_id'] ?? null;

// Kiểm tra tham số
if (empty($feature_id)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số feature_id.']);
    exit();
}

// Kiểm tra xem feature có tồn tại không
$sql_check = "SELECT * FROM `features` WHERE `id` = ?";
$existing_feature = $p->execute_query($sql_check, [$feature_id]);

if (empty($existing_feature)) {
    http_response_code(404); // Not Found
    echo json_encode(['status' => 'error', 'message' => 'Feature không tồn tại.']);
    exit();
}

// Kiểm tra xem feature có đang liên kết với phòng nào không
$sql_check_room_link = "SELECT * FROM `rooms_features` WHERE `features_id` = ?";
$linked_rooms = $p->execute_query($sql_check_room_link, [$feature_id]);

if (!empty($linked_rooms)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Feature này đang được liên kết với phòng. Không thể xóa feature.']);
    exit();
}

// Xóa feature nếu không có liên kết
$sql_delete = "DELETE FROM `features` WHERE `id` = ?";
$result = $p->execute_query($sql_delete, [$feature_id]);

if ($result) {
    http_response_code(200); // Success
    echo json_encode(['status' => 'success', 'message' => 'Feature đã được xóa thành công.']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Xóa feature không thành công.']);
}
