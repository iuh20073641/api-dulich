<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(!isset($data['booking_id']) || !isset($data['namend']) || !isset($data['cccd']) || !isset($data['participants'])){
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin truyền vào']);
        exit();
    }
    $booking_id = $data['booking_id'];
    $namend = $data['namend'];
    $cccd = $data['cccd'];
    
    // Cập nhật thông tin vào database
    $pre_q = "UPDATE `booking_detail_tour` SET `user_name`=?, `cccd` = ? WHERE `booking_id`=?";
    $pre_v = [$namend, $cccd, $booking_id];
    $pre_res = $p->execute_query($pre_q, $pre_v, 'ssi');

    if ( isset($data['participants'])) {
        $participants = $data['participants'];

        try {
            foreach ($participants as $participant) {

                $name = $conn->real_escape_string($participant['name']);
                $gender = $conn->real_escape_string($participant['gender']);
                $dob = $conn->real_escape_string($participant['dob']);
                // Kiểm tra dữ liệu
                if (isset($participant['id'], $participant['name'], $participant['gender'], $participant['dob'])) {
                    // Cập nhật thông tin vào database
                    $sql = "UPDATE `participants` SET `name`=?, `dob` = ?, `gender` = ? WHERE `id`=?";
                    $value = [$name, $dob, $gender, $participant['id']];
                    $result = $p->execute_query($sql, $value, 'sssi');
                }
            }

            // Phản hồi thành công
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
        } catch (Exception $e) {
            // Phản hồi lỗi
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else{
        echo json_encode(['status' => 'error', 'message' => 'Thông tin người dùng bị lỗi']);
    }
?>