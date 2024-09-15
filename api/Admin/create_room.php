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
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
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
if (!$name || !$area || !$price || !$quantity || !$adult || !$children || !$description) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Kiểm tra phòng đã tồn tại chưa
$sql_check = "SELECT * FROM `rooms` WHERE `name` = ?";
$existing_room = $p->execute_query($sql_check, [$name]);

// // In kết quả ra để kiểm tra
// var_dump($existing_room);

if ($existing_room) {
    echo json_encode(['status' => 'error', 'message' => 'Phòng đã tồn tại.']);
    exit();
}

// Thêm phòng mới nếu chưa tồn tại
$sql_insert = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) VALUES (?,?,?,?,?,?,?)";
$values = [$name, $area, $price, $quantity, $adult, $children, $description];
$result = $p->execute_query($sql_insert, $values);

if ($result) {
    // Lấy ID của phòng mới
    $room_id = mysqli_insert_id($p->get_connection());

    // Thêm các facilities vào bảng rooms_facilities
    if (!empty($facilities)) {
        $sql_facility = "INSERT INTO `rooms_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
        foreach ($facilities as $facility) {
            $p->execute_query($sql_facility, [$room_id, $facility], 'ii');
        }
    }

    // Thêm các features vào bảng rooms_features
    if (!empty($features)) {
        $sql_feature = "INSERT INTO `rooms_features`(`room_id`, `features_id`) VALUES (?,?)";
        foreach ($features as $feature) {
            $p->execute_query($sql_feature, [$room_id, $feature], 'ii');
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Phòng đã được thêm thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thêm phòng không thành công.']);
}
