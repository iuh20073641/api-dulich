<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include("../../myclass/clsapi.php");
    
    $p = new clsapi();
    
    // Đọc và giải mã dữ liệu JSON từ yêu cầu PUT
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    
        // Kiểm tra các tham số cần thiết
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'lỗi thông tin gửi đi']);
        } else {

            $depart_id = $data['depart_id'];
            $id_tour = $data['id_tour'];
            $day = $data['day'];
            $order = $data['order'];

            if (!isset($depart_id) || !isset($day) || !isset($order) || !isset($id_tour)) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết']);
                exit();
            }

            // Kiểm tra xem ngày khởi hành này đã có bao nhiêu lượt đặt
            $sql_check = "SELECT * FROM `booking_order_tour` WHERE `departure_id` = ?";
            $stmt_check = $p->get_connection()->prepare($sql_check);
            $stmt_check->bind_param("i", $depart_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $count = $result_check->num_rows;
            // $existing_tour_booking = $result_check->fetch_assoc();

            if($order < $count){
                echo json_encode(['status' => 'error', 'message' => 'Hiện tại đang có số đơn đặt lớn hơn số lượng đơn mà bạn muốn cập nhật']);
                exit();   
            }

            $pre_q = "UPDATE `departure_time` SET `id_tour`=?, `day_depar` = ?, `orders` = ? WHERE `id`=?";
            $pre_v = [$id_tour, $day, $order, $depart_id];
            $pre_res = $p->execute_query($pre_q, $pre_v, 'isii');


            if ($pre_res) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật không thành công']);
            }
        }
   
?>