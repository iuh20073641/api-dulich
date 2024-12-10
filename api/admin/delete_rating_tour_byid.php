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
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $rating_id = $data['rating_id'] ?? null;

    // Kiểm tra tham số
    if (!$rating_id) {
       
        echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu mã đánh giá']);
        exit();
    }

    // Xóa đánh giá tour
    $sql_delete_rating = "DELETE FROM `rating_review_tour` WHERE `sr_no` = ?";
    $result = $p->execute_query($sql_delete_rating, [$rating_id]);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => 'Đánh giá đã được xóa thành công']);
    } else {
       
        echo json_encode(['status' => 'error', 'message' => 'Xóa đánh giá không thành công.']);
    }
?>