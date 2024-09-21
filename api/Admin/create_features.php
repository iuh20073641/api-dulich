<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$name = $data['name'] ?? null;

// Kiểm tra các tham số
if (empty($name)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số name.']);
    exit();
}

// Kiểm tra features đã tồn tại chưa
$sql_check = "SELECT * FROM `features` WHERE `name` = ?";
$existing_features = $p->execute_query($sql_check, [$name]);

if (!empty($existing_features)) {
    http_response_code(409); // Conflict
    echo json_encode(['status' => 'error', 'message' => 'Features đã tồn tại.']);
    exit();
}

// Thêm features mới nếu chưa tồn tại
$sql_insert = "INSERT INTO `features`(`name`) VALUES (?)";
$values = [$name];
$result = $p->execute_query($sql_insert, $values);

if ($result) {
    http_response_code(201); // Created
    echo json_encode(['status' => 'success', 'message' => 'Features đã được thêm thành công.']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Thêm features không thành công.']);
}
