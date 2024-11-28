<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    include("../../myclass/clsapi.php");
    
    $p = new clsapi();

    // Kiểm tra xem có file hình ảnh không
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Không có file hình ảnh hoặc có lỗi xảy ra.']);
        exit();
    }



    function uploadImage($image, $folder)
    {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        $img_mime = $image['type'];

        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img'; // Không phải hình ảnh hợp lệ
        } elseif (($image['size'] / (2024 * 2024)) > 2) {
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
    $iconPath = uploadImage($_FILES['image'], 'carousel');

    if ($iconPath === 'inv_img') {
        echo json_encode(['status' => 'error', 'message' => 'File không phải là hình ảnh hợp lệ.']);
        exit();
    } elseif ($iconPath === 'inv_size') {
        echo json_encode(['status' => 'error', 'message' => 'Kích thước file quá lớn.']);
        exit();
    } elseif ($iconPath === 'dir_not_found') {
        echo json_encode(['status' => 'error', 'message' => 'Thư mục tải lên không tồn tại.']);
        exit();
    } elseif ($iconPath === 'upd_failed') {
        echo json_encode(['status' => 'error', 'message' => 'Tải lên hình ảnh thất bại.']);
        exit();
    }

    // Thêm hình ảnh tour mới vào cơ sở dữ liệu
    $sql_insert = "INSERT INTO `carousel`(`image`) VALUES (?)";
    $values = [$iconPath];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        $imageUrl = "http://localhost:88/api_travel/api/Images/carousel/" . $iconPath;
        http_response_code(201); // Đã tạo
        echo json_encode(['status' => 'success', 'message' => 'Hình ảnh đã được thêm thành công.', 'image_url' => $imageUrl]);
    } else {
        http_response_code(500); // Lỗi máy chủ nội bộ
        echo json_encode(['status' => 'error', 'message' => 'Thêm hình ảnh không thành công.']);
    }
?>