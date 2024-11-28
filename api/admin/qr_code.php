<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Lấy tham số từ yêu cầu GET
    // $booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $booking_id = $data['booking_id'] ?? null;

    // Kiểm tra nếu booking_id không rỗng và là số
    if (!empty($booking_id) && is_numeric($booking_id)) {
        // Thực hiện truy vấn SQL an toàn
        $query = "SELECT bo.*, bd.* FROM `booking_order_tour` bo
            INNER JOIN `booking_detail_tour` bd ON bo.booking_id = bd.booking_id
            WHERE bo.booking_id = ?";
        $result = $p->execute_query($query, [$booking_id]);

        // Trả về kết quả dưới dạng JSON
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid booking_id']);
    }
?>