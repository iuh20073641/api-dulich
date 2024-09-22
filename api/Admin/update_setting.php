<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu PUT
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$site_title = $data['site_title'] ?? null;
$site_about = $data['site_about'] ?? null;

// Kiểm tra các tham số
if (!$site_title || !$site_about) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Cập nhật settings
$sql_update = "UPDATE `settings` SET `site_title` = ?, `site_about` = ? WHERE `sr_no` = 1";
$values = [$site_title, $site_about];
$result = $p->execute_query($sql_update, $values);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Settings đã được cập nhật thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cập nhật settings không thành công.']);
}
