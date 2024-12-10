<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu GET
$search_query = $_GET['query'] ?? '';

if (empty($search_query)) {
    http_response_code(400); // Bad request
    echo json_encode(['status' => 'error', 'message' => 'Thiếu từ khóa tìm kiếm.']);
    exit();
}

// Tìm kiếm nhân viên, loại trừ nhân viên có id là 9
$sql_search = "SELECT * FROM `employees` WHERE (`username` LIKE ? OR `tennhanvien` LIKE ? OR `email` LIKE ? OR `phoneNumber` LIKE ? OR `address` LIKE ? OR `role` LIKE ?) AND `id` != 9";
$search_term = '%' . $search_query . '%';
$employees = $p->execute_query($sql_search, [$search_term, $search_term, $search_term, $search_term, $search_term, $search_term]);

if ($employees) {
    http_response_code(200); // Success
    echo json_encode($employees);
} else {
    //http_response_code(404); // Not found
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy nhân viên nào.']);
}
