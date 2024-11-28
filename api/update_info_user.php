<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra các tham số cần thiết
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số']);
        } else {
            $currentDate = strtotime(date('Y-m-d')); // Lấy thời gian hiện tại
            if(!empty($data['user_id']) && !empty($data['name']) && !empty($data['phone']) && !empty($data['dob']) && !empty($data['address']) ){
                $user_id = $data['user_id'];
                $name = $data['name'];
                $phone = $data['phone'];
                $dob = $data['dob'];
                $address = $data['address'];

                if (strtotime($dob) > $currentDate) {
       
                    echo json_encode(['status' => 'error', 'message' => 'ngày sinh không hợp lệ']);
                    exit();
                }
            

                $pre_q = "UPDATE `user_cred` SET `name`=?, `phonenum` = ?, `dob` = ?, `address` = ? WHERE `id`=?";
                $pre_v = [$name, $phone, $dob, $address, $user_id];
                $pre_res = $p->execute_query($pre_q, $pre_v, 'ssssi');


                if ($pre_res) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật không thành công']);
                }
            } elseif(!empty($data['pass']) && !empty($data['rt_pass'])){
                $user_id = $data['user_id'];
                $pass = $data['pass'];
                $rt_pass = $data['rt_pass'];

                if ($pass === null || strlen($pass) < 6) {
                    echo json_encode(['status' => 'warning', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
                    exit();
                }

                if($pass = $rt_pass){
                    $enc_pass = password_hash($pass, PASSWORD_BCRYPT);

                    $pre_q = "UPDATE `user_cred` SET `password`=? WHERE `id`=?";
                    $pre_v = [$enc_pass, $user_id];
                    $pre_res = $p->execute_query($pre_q, $pre_v, 'si');


                    if ($pre_res) {
                        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Cập nhật không thành công']);
                    }
                } else {
                    echo json_encode(['status' => 'warning', 'message' => 'Nhập lại mật khẩu không đúng']);
                    exit();
                }
            } 
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    }
?>