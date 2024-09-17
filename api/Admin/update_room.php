<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
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
$room_id = $data['room_id'] ?? null;
$name = $data['name'] ?? null;
$area = $data['area'] ?? null;
$price = $data['price'] ?? null;
$quantity = $data['quantity'] ?? null;
$adult = $data['adult'] ?? null;
$children = $data['children'] ?? null;
$description = $data['description'] ?? null;
$features = $data['features'] ?? [];
$facilities = $data['facilities'] ?? [];

// Kiểm tra các tham số
if (!$room_id || !$name || !$area || !$price || !$quantity || !$adult || !$children || !$description) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Cập nhật phòng
$sql_update = "UPDATE `rooms` SET `name` = ?, `area` = ?, `price` = ?, `quantity` = ?, `adult` = ?, `children` = ?, `description` = ? WHERE `id` = ?";
$values = [$name, $area, $price, $quantity, $adult, $children, $description, $room_id];
$result = $p->execute_query($sql_update, $values);

if ($result) {
    // Xóa các facilities và features cũ liên quan đến phòng
    $p->execute_query("DELETE FROM `rooms_facilities` WHERE `room_id` = ?", [$room_id], 'i');
    $p->execute_query("DELETE FROM `rooms_features` WHERE `room_id` = ?", [$room_id], 'i');

    // Thêm các facilities mới
    if (!empty($facilities)) {
        $sql_facility = "INSERT INTO `rooms_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
        foreach ($facilities as $facility) {
            $p->execute_query($sql_facility, [$room_id, $facility], 'ii');
        }
    }

    // Thêm các features mới
    if (!empty($features)) {
        $sql_feature = "INSERT INTO `rooms_features`(`room_id`, `features_id`) VALUES (?,?)";
        foreach ($features as $feature) {
            $p->execute_query($sql_feature, [$room_id, $feature], 'ii');
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Phòng đã được cập nhật thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cập nhật phòng không thành công.']);
}
