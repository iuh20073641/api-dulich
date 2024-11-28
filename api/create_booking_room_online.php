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
    $room_id = $data['room_id'] ?? null;
    $check_in = $data['check_in'] ?? null;
    $check_out = $data['check_out'] ?? null;
    $room_name = $data['room_name'] ?? null;
    $price = $data['price'] ?? null;
    $total_pay = $data['total_pay'] ?? null;
    $user_name = $data['user_name'] ?? null;
    $phonenum = $data['phonenum'] ?? null;
    $address = $data['address'] ?? null;
    $cccd = $data['cccd'] ?? null;

    $pay = 'Đã thanh toán';

    // Kiểm tra các tham số
    if (!$user_id || !$room_id || !$check_in || !$check_out || !$room_name || !$price || !$total_pay || !$user_name || !$phonenum || !$address || !$cccd) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
        exit();
    }

    // Thêm đơn đặt phòng mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO booking_order (user_id, room_id, check_in, check_out, order_id) VALUES (?, ?, ?, ?, ?)";
    $values = [$user_id, $room_id, $check_in, $check_out, $pay];
    $result = $p->execute_query($sql_insert, $values, 'iisss');

    if ($result) {
        $booking_id = mysqli_insert_id($p->get_connection());

        $conn = $p->get_connection(); // Lấy kết nối từ đối tượng $p

        $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `cccd`, `address`) VALUES (?,?,?,?,?,?,?,?)";
        $details_params = [$booking_id, $room_name, $price, $total_pay, $user_name, $phonenum, $cccd, $address];
        $result2 = $p->execute_query($query2, $details_params, 'isiissss');

        if ($result2) {
            echo json_encode(['status' => 'success', 'message' => 'Bạn đã thanh toán thành công.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Thanh toán không thành công.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thanh toán không thành công.']);
    }
?>