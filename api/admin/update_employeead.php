<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu PUT
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
    exit();
}

// Log dữ liệu nhận được để kiểm tra
error_log(print_r($data, true));

// Lấy dữ liệu từ yêu cầu
$id = $data['id'] ?? null;
$tennhanvien = $data['tennhanvien'] ?? null;
$password = $data['password'] ?? null;
$originalPassword = $data['originalPassword'] ?? null; // Thêm tham số originalPassword
$email = $data['email'] ?? null;
$phoneNumber = $data['phoneNumber'] ?? null;
$address = $data['address'] ?? null;
$role = $data['role'] ?? null;

// Kiểm tra các tham số
if ($id === null || $tennhanvien === null || $password === null || $originalPassword === null || $email === null || $phoneNumber === null || $address === null || $role === null) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Mã hóa mật khẩu nếu nó được thay đổi
if ($password !== $originalPassword) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
} else {
    $hashedPassword = $password;
}

// Cập nhật thông tin nhân viên
$sql_update = "UPDATE `employees` SET `tennhanvien` = ?, `password` = ?, `email` = ?, `phoneNumber` = ?, `address` = ? , `role` = ? WHERE `id` = ?";
$values = [$tennhanvien, $hashedPassword, $email, $phoneNumber, $address, $role, $id];
$result = $p->execute_query($sql_update, $values);

if ($result) {
    // Trả về dữ liệu nhân viên đã cập nhật
    $updatedEmployee = [
        'id' => $id,
        'tennhanvien' => $tennhanvien,
        'password' => $hashedPassword,
        'email' => $email,
        'phoneNumber' => $phoneNumber,
        'address' => $address,
        'role' => $role
    ];
    echo json_encode(['status' => 'success', 'message' => 'Thông tin đã được cập nhật thành công.', 'updatedEmployee' => $updatedEmployee]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cập nhật thông tin không thành công.']);
}
