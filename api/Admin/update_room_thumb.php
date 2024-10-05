<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

// Tạo một thể hiện của lớp
$p = new clsapi();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Đọc và giải mã dữ liệu JSON từ yêu cầu
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Kiểm tra các tham số cần thiết
    if (isset($data['room_id']) && isset($data['image_id'])) {
        $room_id = intval($data['room_id']);
        $image_id = intval($data['image_id']);

        // Đặt tất cả các hình ảnh của phòng cụ thể thành không phải thumbnail
        $pre_q = "UPDATE `rooms_images` SET `thumb`=? WHERE `room_id`=?";
        $pre_v = [0, $room_id];
        $pre_res = $p->execute_query($pre_q, $pre_v, 'ii');

        // Đặt hình ảnh cụ thể thành thumbnail
        $q = "UPDATE `rooms_images` SET `thumb`=? WHERE `sr_no`=? AND `room_id`=?";
        $v = [1, $image_id, $room_id];
        $res = $p->execute_query($q, $v, 'iii');

        if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Thumbnail đã được cập nhật thành công.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cập nhật thumbnail không thành công.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số room_id hoặc image_id.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ.']);
}