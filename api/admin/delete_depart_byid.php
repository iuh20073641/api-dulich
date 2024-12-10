<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu DELETE
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $depart_id = $data['depart_id'] ?? null;

    // Kiểm tra tham số
    if (empty($depart_id)) {
       
        echo json_encode(['status' => 'error', 'message' => 'Thiếu mã ngày khởi hành']);
        exit();
    }

    // Kiểm tra xem feature có tồn tại không
    $sql_check = "SELECT * FROM `departure_time` WHERE `id` = ?";
    $existing_depart = $p->execute_query($sql_check, [$depart_id]);

    if (empty($existing_depart)) {
        
        echo json_encode(['status' => 'error', 'message' => 'Ngày khởi hành này không tồn tại']);
        exit();
    }

    // Kiểm tra xem ngày khởi hành này đã có người đặt chưa
    $sql_check_room_link = "SELECT * FROM `booking_order_tour` WHERE `departure_id` = ?";
    $linked_booking = $p->execute_query($sql_check_room_link, [$depart_id]);

    if (!empty($linked_booking)) {
       
        echo json_encode(['status' => 'error', 'message' => 'Ngày khởi hành này đã có người đặt']);
        exit();
    }

    // Xóa ngày khởi hành nếu không có liên kết
    $sql_delete = "DELETE FROM `departure_time` WHERE `id` = ?";
    $result = $p->execute_query($sql_delete, [$depart_id]);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => 'Ngày khởi hành đã được xóa thành công.']);
    } else {
      
        echo json_encode(['status' => 'error', 'message' => 'Xóa ngày khởi hành không thành công.']);
    }
?>