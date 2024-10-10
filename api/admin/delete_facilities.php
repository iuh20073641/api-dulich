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
        echo json_encode(['status' => 'error1', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $facility_id = $data['facility_id'] ?? null;

    // Kiểm tra tham số
    if (empty($facility_id)) {
        echo json_encode(['status' => 'error2', 'message' => 'Thiếu hoặc không hợp lệ tham số facility_id.']);
        exit();
    }

    // Kiểm tra xem facility có tồn tại không
    $sql_check = "SELECT * FROM `facilities` WHERE `id` = ?";
    $existing_facility = $p->execute_query($sql_check, [$facility_id]);

    if (empty($existing_facility)) {
        echo json_encode(['status' => 'error3', 'message' => 'Facility không tồn tại.']);
        exit();
    }

    // Kiểm tra xem facility có đang liên kết với phòng nào không
    $sql_check_room_link = "SELECT * FROM `rooms_facilities` WHERE `facilities_id` = ?";
    $linked_rooms = $p->execute_query($sql_check_room_link, [$facility_id]);

    if (!empty($linked_rooms)) {
        echo json_encode(['status' => 'error4', 'message' => 'Facility này đang được liên kết với phòng. Không thể xóa facility.']);
        exit();
    }

    // Xóa facility nếu không có liên kết
    $sql_delete = "DELETE FROM `facilities` WHERE `id` = ?";
    $result = $p->execute_query($sql_delete, [$facility_id]);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Facility đã được xóa thành công.']);
    } else {
        echo json_encode(['status' => 'error5', 'message' => 'Xóa facility không thành công.']);
    }
?>