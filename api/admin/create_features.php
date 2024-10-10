<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Đọc và giải mã dữ liệu JSON từ yêu cầu POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        
        echo json_encode(['status' => 'error1', 'message' => 'Dữ liệu JSON không hợp lệ.']);
        exit();
    }

    // Lấy dữ liệu từ yêu cầu
    $name = $data['name'] ?? null;

    // Kiểm tra các tham số
    if (empty($name)) {
       
        echo json_encode(['status' => 'error2', 'message' => 'Thiếu hoặc không hợp lệ tham số name.']);
        exit();
    }

    // Kiểm tra features đã tồn tại chưa
    $sql_check = "SELECT * FROM `features` WHERE `name` = ?";
    $existing_features = $p->execute_query($sql_check, [$name]);

    if (!empty($existing_features)) {
       
        echo json_encode(['status' => 'error3', 'message' => 'Features đã tồn tại.']);
        exit();
    }

    // Thêm features mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO `features`(`name`) VALUES (?)";
    $values = [$name];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => 'Features đã được thêm thành công.']);
    } else {
       
        echo json_encode(['status' => 'error4', 'message' => 'Thêm features không thành công.']);
    }
?>