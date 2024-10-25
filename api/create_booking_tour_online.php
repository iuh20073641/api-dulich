<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Lấy dữ liệu từ yêu cầu
$user_id = $data['user_id'] ?? null;
$id_tour = $data['id_tour'] ?? null;
$depar_id = $data['depar_id'] ?? null;
$participant = $data['participant'] ?? null;
$price_tour = $data['price_tour'] ?? null;
$name_user = $data['name_user'] ?? null;
$phone = $data['phone'] ?? null;
$address = $data['address'] ?? null;
$tour_name = $data['tour_name'] ?? null;
$total_pay = $data['totalPay'] ?? null;
$cccd = $data['cccd'] ?? null;
$customer = $data['customers'] ?? null;


$pay = 'Đã thanh toán';

// Kiểm tra các tham số
if (!$user_id || !$id_tour || !$depar_id || !$participant || !$price_tour || !$name_user || !$phone || !$address || !$tour_name || !$total_pay || !$customer || !$cccd) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Thêm tour mới nếu chưa tồn tại
$sql_insert = "INSERT INTO booking_order_tour (user_id, tour_id, departure_id, participant, order_id) VALUES (?, ?, ?, ?, ?)";
$values = [$user_id, $id_tour, $depar_id, $participant, $pay];
$result = $p->execute_query($sql_insert, $values, 'iiiss');

if ($result) {
    $booking_id = mysqli_insert_id($p->get_connection());

    $conn = $p->get_connection(); // Lấy kết nối từ đối tượng $p
    foreach ($data['customers'] as $customer) {
        $name = $conn->real_escape_string($customer['name']);
        $cccd = $conn->real_escape_string($customer['cccd']);
    
        $sql = "INSERT INTO participants (booking_id, name, cccd) VALUES (?, ?, ?)";
        $values3 = [$booking_id, $name, $cccd];
        $result3 = $p->execute_query($sql, $values3);
    }

    $query2 = "INSERT INTO `booking_detail_tour`(`booking_id`, `tour_name`, `price`, `total_pay`, `cccd`, `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?,?)";
    $details_params = [$booking_id, $tour_name, $price_tour, $total_pay, $cccd, $name_user, $phone, $address];
    $result2 = $p->execute_query($query2, $details_params, 'isssssss');

    if ($result2) {
        echo json_encode(['status' => 'success', 'message' => 'Bạn đã thanh toán thành công.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thanh toán không thành công.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Thanh toán không thành công.']);
}
?>