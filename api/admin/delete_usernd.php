<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
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
    $user_id = $data['user_id'] ?? null;

    // Kiểm tra tham số
    if (!$user_id) {
       
        echo json_encode(['status' => 'error2', 'message' => 'Thiếu hoặc không hợp lệ tham số user ID.']);
        exit();
    }

    // Kiểm tra người dùng có tồn tại không
    $sql_check = "SELECT * FROM `user_cred` WHERE `id` = ?";
    $user_exists = $p->execute_query($sql_check, [$user_id]);

    if (!$user_exists) {
       
        echo json_encode(['status' => 'error3', 'message' => 'Người dùng không tồn tại.']);
        exit();
    }

    // Xóa người dùng
    $sql_delete_user = "DELETE FROM `user_cred` WHERE `id` = ?";
    $result = $p->execute_query($sql_delete_user, [$user_id]);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => 'Người dùng đã được xóa thành công.']);
    } else {
       
        echo json_encode(['status' => 'error4', 'message' => 'Xóa người dùng không thành công.']);
    }
?>