<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    // Kiểm tra xem có file hình ảnh không
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error1', 'message' => 'Không có file hình ảnh hoặc có lỗi xảy ra.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $date = $_POST['day'] ?? null;
    $schedule = $_POST['schedule'] ?? null;
    $locations = $_POST['locations'] ?? null;
    $id_tour = $_POST['id_tour'] ?? null; // Thêm id_tour

    // Kiểm tra các tham số
    if (empty($date) || empty($schedule) || empty($locations) || empty($id_tour)) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error2', 'message' => 'Thiếu thông tin cần thiết']);
        exit();
    }

    // Kiểm tra xem id_tour có tồn tại trong bảng tour1 không
    $sql_check_tour = "SELECT id FROM tours WHERE id = ?";
    $stmt_check_tour = $p->get_connection()->prepare($sql_check_tour);
    $stmt_check_tour->bind_param("i", $id_tour);
    $stmt_check_tour->execute();
    $result_check_tour = $stmt_check_tour->get_result();

    if ($result_check_tour->num_rows === 0) {
        // http_response_code(400); // Yêu cầu không hợp lệ
        echo json_encode(['status' => 'error3', 'message' => 'id_tour không tồn tại.']);
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

    // Thêm lịch trình tour mới vào cơ sở dữ liệu
    $sql_insert = "INSERT INTO `tour_schedule`(`id_tour`, `date`, `image`, `schedule`, `locations`) VALUES (?, ?, ?, ?, ?)";
    $values = [$id_tour, $date, $iconPath, $schedule, $locations];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        $imageUrl = "http://localhost:88/api_travel/api/Images/lichtrinhtour/" . $iconPath;
        // http_response_code(201); // Đã tạo
        echo json_encode(['status' => 'success', 'message' => 'Lịch trình tour đã được thêm thành công.', 'image_url' => $imageUrl]);
    } else {
        // http_response_code(500); // Lỗi máy chủ nội bộ
        echo json_encode(['status' => 'error8', 'message' => 'Thêm lịch trình tour không thành công.']);
    }
?>