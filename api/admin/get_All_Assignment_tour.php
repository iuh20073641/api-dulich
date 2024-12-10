<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    // Xử lý yêu cầu OPTIONS trước khi xử lý các yêu cầu khác
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }

    // Kiểm tra phương thức HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Phương thức HTTP không hợp lệ.']);
        exit();
    }

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy staff_id từ dữ liệu Get
    $staff_id = $data['staff_id'] ?? null;

    if (!$staff_id) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số mã hướng dẫn viên.']);
        exit();
    }

    // Thực hiện truy vấn SQL
    $query = "SELECT bo.*, bd.*, dt.* FROM `booking_order_tour` bo
        INNER JOIN `booking_detail_tour` bd ON bo.booking_id = bd.booking_id
        INNER JOIN `departure_time` dt ON bo.departure_id = dt.id
        INNER JOIN `assignment-tour` a ON bo.booking_id = a.booking_id
        WHERE (a.staff_id = ?)
        AND (bo.refund is null)
        ORDER BY dt.day_depar ASC";

    $result = $p->execute_query($query, [$staff_id], 'i');

    if ($result) {
        echo json_encode(['status' => 'success', 'data' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'không có lịch trình được phân công.']);
    }
?>