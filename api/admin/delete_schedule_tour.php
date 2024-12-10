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
    $schedule_id = $data['schedule_id'] ?? null;

    // Kiểm tra tham số
    if (!$schedule_id) {
        echo json_encode(['status' => 'error', 'message' => 'Không có mã lịch trình']);
        exit();
    }

    // Kiểm tra xem room image có tồn tại không
    $sql_check = "SELECT * FROM `tour_schedule` WHERE `id` = ?";
    $stmt_check = $p->get_connection()->prepare($sql_check);
    $stmt_check->bind_param("i", $schedule_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $existing_tour_image = $result_check->fetch_assoc();

    // Xóa lịch trình
    $sql_delete_tour = "DELETE FROM `tour_schedule` WHERE `id` = ?";
    $result = $p->execute_query($sql_delete_tour, [$schedule_id]);


    if ($result) {
         // Xóa file hình ảnh khỏi thư mục
         $image_path = __DIR__ . '/../Images/lichtrinhtour/' . $existing_tour_image['image'];
         if (file_exists($image_path)) {
             unlink($image_path);
         }
        echo json_encode(['status' => 'success', 'message' => 'Lịch trình trong ngày này đã được xóa thành công.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Xóa lịch trình không thành công.']);
    }
?>