<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../myclass/clsapi.php");

    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $user_id = $data['user_id'] ?? null;
    $id_tour = $data['id_tour'] ?? null;
    $depar_id = $data['depar_id'] ?? null;
    $participant = $data['participant'] ?? null;

    // Kiểm tra các tham số
    if (!$user_id || !$id_tour || !$depar_id || !$participant) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
        exit();
    }

    // Thêm tour mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO booking_order_tour (user_id, tour_id, departure_id, participant) VALUES (?, ?, ?, ?)";
    $values = [$user_id, $id_tour, $depar_id, $participant];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Bạn đã đặt chỗ thành công.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Đặt chỗ tour không thành công.']);
    }
?>