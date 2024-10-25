<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // if (isset($_POST['assign_room'])) {
        // Lọc dữ liệu từ $_POST
        // $frm_data = filteration($_POST);

        $query = "UPDATE `booking_order_tour` bo
                SET bo.arrival = ?, bo.rate_review = ?
                WHERE bo.booking_id = ?";

        $values = [1, 0, $data['booking_id']];

        $res = $p->execute_query($query, $values, 'iii');

        // echo json_encode(['success' => $res == 1]);
        if($res){
            echo json_encode(['status' => 'success', 'message' => 'Đơn đặt tour đã được duyệt']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'Xác nhân đơn đặt tour thất bại.']);
        }
    // }

    // Hàm lọc dữ liệu
    function filteration($data) {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(strip_tags($value));
        }
        return $data;
    }
?>