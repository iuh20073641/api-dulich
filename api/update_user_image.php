<?php 
    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");
    $p = new clsapi();

    if (isset($_FILES["image"])) {
        $user_id = $_REQUEST["user_id"] ?? null;

        $ch = curl_init();
        $cfile = new CURLFile($_FILES["image"]["tmp_name"], $_FILES["image"]["type"], $_FILES["image"]["name"]);
        $data = array("file" => $cfile);

        curl_setopt($ch, CURLOPT_URL, "http://localhost:88/api_travel/api/upload_image.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $response_data = json_decode($response, true);
        if (isset($response_data["status"]) && $response_data["status"] == "success") {
            $image = $response_data["path"];

            
                // Kiểm tra xem image có tồn tại không
                $sql_check = "SELECT * FROM `user_cred` WHERE `id` = ?";
                $stmt_check = $p->get_connection()->prepare($sql_check);
                $stmt_check->bind_param("i", $user_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                $existing_tour_image = $result_check->fetch_assoc();
                // Xóa file hình ảnh khỏi thư mục
                $image_path = __DIR__ . '/Images/user/' . $existing_tour_image['profile'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                // Update image trên database
                $pre_q = "UPDATE `user_cred` SET `profile`=? WHERE `id`=?";
                $pre_v = [$image, $user_id];
                $pre_res = $p->execute_query($pre_q, $pre_v, 'si');

                if ($pre_res) {
                    echo json_encode(['status' => 'success', 'message' => 'Cập nhật hình ảnh thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Cập nhật hình ảnh mới không thành công']);
                }

        } else {
            echo json_encode(["status" => "error", "message" => $response_data["message"] ?? 'Lỗi tải lên hình ảnh']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không nhận được hình ảnh']);
    }
?>