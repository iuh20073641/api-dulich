<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    $booking_id = $data["booking_id"] ?? null;
    $staff_id = $data["staff_id"] ?? null;

    // Kiểm tra các tham số
    if (empty($booking_id) ) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết booking']);
        exit();
    }

    if (empty($staff_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết nhân viên']);
        exit();
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $check = $p->execute_query("SELECT * FROM `assignment-tour` WHERE `booking_id` = ? AND `staff_id` = ?", [$booking_id, $staff_id]);

    if ($check && count($check) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tour này đã được phân công hướng dẫn viên']);
    } else {
        // Thêm nhân viên mới vào cơ sở dữ liệu
        $sql = "INSERT INTO `assignment-tour` (booking_id, staff_id) VALUES (?, ?)";
        $params = [$booking_id, $staff_id];
        $result = $p->execute_query($sql, $params);

        if ($result) {
            $query = "UPDATE `booking_order_tour` bo
                    SET bo.arrival = ?, bo.rate_review = ?
                    WHERE bo.booking_id = ?";

            $values = [1, 0, $booking_id];

            $res = $p->execute_query($query, $values, 'iii');

            echo json_encode(['status' => 'success', 'message' => 'Phân công hướng dẫn viên thành công']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phân công hướng dẫn viên không thành công']);
        }
    }
?>