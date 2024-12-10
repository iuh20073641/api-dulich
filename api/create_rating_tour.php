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
    $booking_id = $data['booking_id'] ?? null;
    $tour_id = $data['tour_id'] ?? null;
    $rating = $data['rating'] ?? null;
    $review = $data['review'] ?? null;
    $dateTime = new DateTime();
    $formattedDateTime = $dateTime->format('Y-m-d H:i:s'); // Chuyển sang định dạng phù hợp với MySQL

    // Kiểm tra các tham số
    if (!$user_id || !$booking_id || !$tour_id || !$rating || !$review ) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
        exit();
    }

    // Kiểm tra tour đã được đánh giá chưa
    $sql_check = "SELECT * FROM `rating_review_tour` WHERE `user_id`= ? AND `booking_id`= ?";
    $existing_rating = $p->execute_query($sql_check, [$user_id, $booking_id]);

    if (!empty($existing_rating)) {
        echo json_encode(['status' => 'error', 'message' => 'Bạn đã đánh giá tour này trước đó']);
        exit();
    }

    // Thêm đánh giá nếu chưa đánh giá
    $sql_insert = "INSERT INTO `rating_review_tour`(`booking_id`, `tour_id`, `user_id`, `rating`, `review`, `datetime`) VALUES (?,?,?,?,?,?)";
    $values = [$booking_id, $tour_id, $user_id, $rating, $review, $formattedDateTime];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Đánh giá tour thành công.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Đánh giá tour không thành công.']);
    }

?>