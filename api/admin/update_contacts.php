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

    // if(empty($data['map_link']) ){
    //     echo json_encode(['status' => 'error', 'message' => 'thiếu dữ liệu', 'data' => $data['map_link']]);
    //     exit();
    // }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra các tham số cần thiết
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số']);
        } else {

            $address = $data['address'];
            $map_link = $data['map_link'];
            $phone1 = $data['phone1'];
            $phone2 = $data['phone2'];
            $email = $data['email'];
            $fb = $data['fb'];
            $tw = $data['tw'];
            $insta = $data['insta'];
            $jframe = $data['jframe'];

            $pre_q = "UPDATE `contact_details` SET `address`=?, `gmap` = ?, `pn1` = ?, `pn2` = ?, `email` = ?, `tw` = ?, `fb` = ?, `insta` = ?, `jframe` = ?  WHERE `sr_no`=?";
            $pre_v = [$address, $map_link, $phone1, $phone2, $email, $tw, $fb, $insta, $jframe, 1];
            $pre_res = $p->execute_query($pre_q, $pre_v, 'sssssssssi');


            if ($pre_res) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật không thành công']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    }
?>