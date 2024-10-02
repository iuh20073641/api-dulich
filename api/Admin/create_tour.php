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
$price = $data['price'] ?? null;
$description = $data['description'] ?? null;
$timetour = $data['timetour'] ?? null;
$depart = $data['depart'] ??  null;
$departurelocation = $data['departurelocation'] ?? null;
$discount = $data['discount'] ??  null;
$itinerary = $data['itinerary'] ??  null;
$vehicle = $data['vehicle'] ??  null;

// Kiểm tra các tham số
if (!$name || !$price || !$description || !$timetour || !$depart || !$departurelocation || !$discount || !$itinerary || !$vehicle) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Kiểm tra tour đã tồn tại chưa
$sql_check = "SELECT * FROM `tour1` WHERE `name` = ?";
$existing_tour = $p->execute_query($sql_check, [$name]);

if ($existing_tour && count($existing_tour) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Tour đã tồn tại.']);
    exit();
}

// Thêm tour mới nếu chưa tồn tại
$sql_insert = "INSERT INTO `tour1`(`name`, `price`, `description`, `timetour`, `depart`, `departurelocation`, `discount`, `itinerary`, `vehicle`) VALUES (?,?,?,?,?,?,?,?,?)";
$values = [$name, $price, $description, $timetour, $depart, $departurelocation, $discount, $itinerary, $vehicle];
$result = $p->execute_query($sql_insert, $values);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Tour đã được thêm thành công.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thêm tour không thành công.']);
}
