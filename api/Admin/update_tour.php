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
$tour_id = $data['tour_id'] ?? null;
$name = $data['name'] ?? null;
$price = $data['price'] ?? null;
$description = $data['description'] ?? null;
$timetour = $data['timetour'] ?? null;
$depart = $data['depart'] ?? null;
$departurelocation = $data['departurelocation'] ?? null;
$discount = $data['discount'] ?? null;
$itinerary = $data['itinerary'] ?? null;
$vehicle = $data['vehicle'] ?? null;

// Kiểm tra các tham số
if ($tour_id === null || $name === null || $price === null || $description === null || $timetour === null || $depart === null || $departurelocation === null || $discount === null || $itinerary === null || $vehicle === null) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Cập nhật thông tin tour
$sql_update = "UPDATE `tour1` SET `name` = ?, `price` = ?, `description` = ?, `timetour` = ?, `depart` = ?, `departurelocation` = ?, `discount` = ?, `itinerary` = ?, `vehicle` = ? WHERE `id` = ?";
$values = [$name, $price, $description, $timetour, $depart, $departurelocation, $discount, $itinerary, $vehicle, $tour_id];
$result = $p->execute_query($sql_update, $values);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Thông tin tour đã được cập nhật thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cập nhật thông tin tour không thành công.']);
}
