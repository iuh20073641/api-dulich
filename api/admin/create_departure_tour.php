<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $tour_id = $data['id_tour'] ?? null;
    $day_depar = $data['day_depar'] ?? null;
    $orders = $data['orders'] ?? null;

    // Kiểm tra các tham số
    if (!$tour_id || !$day_depar || !$orders) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ tham số truyền vào.']);
        exit();
    }

    // Kiểm tra xem id_tour có tồn tại trong bảng tour1 không
    $sql_check_tour = "SELECT id FROM tours WHERE id = ?";
    $stmt_check_tour = $p->get_connection()->prepare($sql_check_tour);
    $stmt_check_tour->bind_param("i", $tour_id);
    $stmt_check_tour->execute();
    $result_check_tour = $stmt_check_tour->get_result();

    if ($result_check_tour->num_rows === 0) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error', 'message' => 'id_tour không tồn tại.']);
        exit();
    }

    // Kiểm tra ngày khởi hành đã tồn tại chưa
    $sql_check = "SELECT * FROM `departure_time` WHERE `day_depar` = ? AND `id_tour` = ?";
    $existing_depart_time = $p->execute_query($sql_check, [$day_depar, $tour_id]);

    if (!empty($existing_depart_time)) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error', 'message' => 'Ngày khởi hành này đã tồn tại.']);
        exit();
    }

    // Thêm ngày khởi hành mới vào cơ sở dữ liệu
    $sql_insert = "INSERT INTO `departure_time`(`id_tour`, `day_depar`, `orders`) VALUES (?, ?, ?)";
    $values = [$tour_id, $day_depar, $orders];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        // http_response_code(201); // Đã tạo
        echo json_encode(['status' => 'success', 'message' => 'Ngày khởi hành đã được thêm thành công.']);
    } else {
        // http_response_code(500); // Lỗi máy chủ nội bộ
        echo json_encode(['status' => 'error', 'message' => 'Khởi tạo ngày khởi hành không thành công.']);
    }
?>