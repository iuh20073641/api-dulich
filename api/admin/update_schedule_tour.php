<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    // Kiểm tra xem có file hình ảnh không
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK || $_FILES['image'] === null) {
        // Lấy dữ liệu từ yêu cầu
        $date = $_POST['day'] ?? null;
        $schedule = $_POST['schedule'] ?? null;
        $locations = $_POST['locations'] ?? null;
        $id_schedule = $_POST['id_schedule'] ?? null; 

        // Kiểm tra các tham số
        if (empty($date) || empty($schedule) || empty($locations) || empty($id_schedule)) {
            // http_response_code(400); // Yêu cầu không hợp lệ
            echo json_encode(['status' => 'error2', 'message' => 'Thiếu dữ liệu cần thiết']);
            exit();
        }

        // Thêm lịch trình tour mới vào cơ sở dữ liệu
        $sql_update = "UPDATE `tour_schedule` SET `date` = ?, `schedule` = ?, `locations` = ? WHERE `id` = ?";
        $values = [$date, $schedule, $locations, $id_schedule];
        $result = $p->execute_query($sql_update, $values);

        if ($result) {
            // http_response_code(201); // Đã tạo
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật lịch trình tour thành công']);
        } else {
            // http_response_code(500); // Lỗi máy chủ nội bộ
            echo json_encode(['status' => 'error8', 'message' => 'Cập nhật lịch trình tour không thành công']);
        }
    } else {
        // Lấy dữ liệu từ yêu cầu
        $date = $_POST['day'] ?? null;
        $schedule = $_POST['schedule'] ?? null;
        $locations = $_POST['locations'] ?? null;
        $id_schedule = $_POST['id_schedule'] ?? null; 

        // Kiểm tra các tham số
        if (empty($date) || empty($schedule) || empty($locations) || empty($id_schedule)) {
            // http_response_code(400); // Yêu cầu không hợp lệ
            echo json_encode(['status' => 'error2', 'message' => 'Thiếu dữ liệu cần thiết']);
            exit();
        }

        function uploadImage($image, $folder)
        {
            $valid_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
            $img_mime = $image['type'];

            if (!in_array($img_mime, $valid_mime)) {
                return 'inv_img'; // Không phải hình ảnh hợp lệ
            } elseif (($image['size'] / (10240 * 10240)) > 2) {
                return 'inv_size'; // Kích thước file quá lớn
            } else {
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $rname = 'IMG_' . random_int(11111, 99999) . ".$ext"; // Tạo tên file mới

                // Đường dẫn tới file tải lên
                $uploadDir = __DIR__ . '/../Images/' . $folder . '/';

                // Ghi log thư mục và đường dẫn tải lên
                error_log("Thư mục tải lên: " . $folder);
                error_log("Đường dẫn tải lên: " . $uploadDir);

                // Kiểm tra thư mục tồn tại
                if (!is_dir($uploadDir)) {
                    return 'dir_not_found'; // Thư mục không tồn tại
                }

                // Đường dẫn tới file tải lên
                $img_path = $uploadDir . $rname;

                // Di chuyển file tới thư mục đích
                if (move_uploaded_file($image['tmp_name'], $img_path)) {
                    return $rname; // Trả về tên file đã tải lên
                } else {
                    return 'upd_failed'; // Tải lên thất bại
                }
            }
        }

        // Tải lên biểu tượng
        $iconPath = uploadImage($_FILES['image'], 'lichtrinhtour');

        if ($iconPath === 'inv_img') {
            // http_response_code(400);
            echo json_encode(['status' => 'error4', 'message' => 'File không phải là hình ảnh hợp lệ.']);
            exit();
        } elseif ($iconPath === 'inv_size') {
            // http_response_code(400);
            echo json_encode(['status' => 'error5', 'message' => 'Kích thước file quá lớn.']);
            exit();
        } elseif ($iconPath === 'dir_not_found') {
            // http_response_code(500);
            echo json_encode(['status' => 'error6', 'message' => 'Thư mục tải lên không tồn tại.']);
            exit();
        } elseif ($iconPath === 'upd_failed') {
            // http_response_code(500);
            echo json_encode(['status' => 'error7', 'message' => 'Tải lên hình ảnh thất bại.']);
            exit();
        }

        // Kiểm tra xem room image có tồn tại không
        $sql_check = "SELECT * FROM `tour_schedule` WHERE `id` = ?";
        $stmt_check = $p->get_connection()->prepare($sql_check);
        $stmt_check->bind_param("i", $id_schedule);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $existing_tour_image = $result_check->fetch_assoc();

        // Thêm lịch trình tour mới vào cơ sở dữ liệu
        $sql_update = "UPDATE `tour_schedule` SET `date` = ?, `image` = ?, `schedule` = ?, `locations` = ? WHERE `id` = ?";
        $values = [$date, $iconPath, $schedule, $locations, $id_schedule];
        $result = $p->execute_query($sql_update, $values);

        if ($result) {
            // Xóa file hình ảnh khỏi thư mục
            $image_path = __DIR__ . '/../Images/lichtrinhtour/' . $existing_tour_image['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            // http_response_code(201); // Đã tạo
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật lịch trình tour thành công']);
        } else {
            // http_response_code(500); // Lỗi máy chủ nội bộ
            echo json_encode(['status' => 'error8', 'message' => 'Cập nhật lịch trình tour không thành công']);
        }
    }

    
?>