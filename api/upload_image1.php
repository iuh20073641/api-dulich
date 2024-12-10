<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

try {
    // Đường dẫn đến thư mục lưu trữ hình ảnh
    $target_dir = "../api/Images/news/";

    // Kiểm tra xem thư mục có tồn tại không, nếu không tạo thư mục
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            throw new Exception("Không thể tạo thư mục lưu trữ.");
        }
    }

    // Tạo tên file duy nhất
    $unique_name = uniqid() . '_' . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $unique_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Giới hạn kích thước file (ví dụ: 5MB)
    $max_file_size = 5 * 1024 * 1024; // 5 MB

    // Kiểm tra xem file có phải là hình ảnh không
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            // Kiểm tra định dạng file (chỉ cho phép JPG, JPEG, PNG, GIF)
            if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                // Kiểm tra kích thước file
                if ($_FILES["file"]["size"] <= $max_file_size) {
                    // Di chuyển file vào thư mục đích
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                        // Trả về đường dẫn tương đối
                        echo json_encode(["status" => "success", "path" => $unique_name]);
                    } else {
                        throw new Exception("Có lỗi khi tải file lên. Vui lòng thử lại.");
                    }
                } else {
                    throw new Exception("File vượt quá kích thước cho phép (5MB).");
                }
            } else {
                throw new Exception("Chỉ chấp nhận các định dạng JPG, JPEG, PNG, GIF.");
            }
        } else {
            throw new Exception("File không phải là hình ảnh.");
        }
    } else {
        throw new Exception("Có lỗi khi tải file lên.");
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
