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
    $feature_id = $data['feature_id'] ?? null;

    // Kiểm tra tham số
    if (empty($feature_id)) {
       
        echo json_encode(['status' => 'error2', 'message' => 'Thiếu hoặc không hợp lệ tham số feature_id.']);
        exit();
    }

    // Kiểm tra xem feature có tồn tại không
    $sql_check = "SELECT * FROM `features` WHERE `id` = ?";
    $existing_feature = $p->execute_query($sql_check, [$feature_id]);

    if (empty($existing_feature)) {
        
        echo json_encode(['status' => 'error3', 'message' => 'Feature không tồn tại.']);
        exit();
    }

    // Kiểm tra xem feature có đang liên kết với phòng nào không
    $sql_check_room_link = "SELECT * FROM `rooms_features` WHERE `features_id` = ?";
    $linked_rooms = $p->execute_query($sql_check_room_link, [$feature_id]);

    if (!empty($linked_rooms)) {
       
        echo json_encode(['status' => 'error4', 'message' => 'Feature này đang được liên kết với phòng. Không thể xóa feature.']);
        exit();
    }

    // Xóa feature nếu không có liên kết
    $sql_delete = "DELETE FROM `features` WHERE `id` = ?";
    $result = $p->execute_query($sql_delete, [$feature_id]);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => 'Feature đã được xóa thành công.']);
    } else {
      
        echo json_encode(['status' => 'error5', 'message' => 'Xóa feature không thành công.']);
    }
?>