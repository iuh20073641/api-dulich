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
    $room_image_id = $data['room_image_id'] ?? null;

    // Kiểm tra tham số
    if (empty($room_image_id)) {
       
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số room_image_id.']);
        exit();
    }

    // Kiểm tra xem room image có tồn tại không
    $sql_check = "SELECT * FROM `rooms_images` WHERE `sr_no` = ?";
    $stmt_check = $p->get_connection()->prepare($sql_check);
    $stmt_check->bind_param("i", $room_image_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $existing_room_image = $result_check->fetch_assoc();

    if (!$existing_room_image) {
        
        echo json_encode(['status' => 'error', 'message' => 'Room image không tồn tại.']);
        exit();
    }

    // Xóa room image nếu không có liên kết
    $sql_delete = "DELETE FROM `rooms_images` WHERE `sr_no` = ?";
    $stmt_delete = $p->get_connection()->prepare($sql_delete);
    $stmt_delete->bind_param("i", $room_image_id);
    $result = $stmt_delete->execute();

    if ($result) {
        // Xóa file hình ảnh khỏi thư mục
        $image_path = __DIR__ . '/../Images/roomimage/' . $existing_room_image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

      
        echo json_encode(['status' => 'success', 'message' => 'Room Image đã được xóa thành công.']);
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Xóa Room Image không thành công.']);
    }
?>