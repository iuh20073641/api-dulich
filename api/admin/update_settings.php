<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra các tham số cần thiết
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số']);
        } else {

            $title_web = $data['title_web'];
            $about_web = $data['about_web'];

            $pre_q = "UPDATE `settings` SET `site_title`=?, `site_about` = ? WHERE `sr_no`=?";
            $pre_v = [$title_web, $about_web, 1];
            $pre_res = $p->execute_query($pre_q, $pre_v, 'ssi');


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