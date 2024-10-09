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
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;
$email = $data['email'] ?? null;
$phoneNumber = $data['phoneNumber'] ?? null;
$address = $data['address'] ?? null;

// Kiểm tra các tham số
if ($id === null || $username === null || $password === null || $email === null || $phoneNumber === null || $address === null) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Mã hóa mật khẩu nếu nó được thay đổi
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Cập nhật thông tin nhân viên
$sql_update = "UPDATE `employees` SET `username` = ?, `password` = ?, `email` = ?, `phoneNumber` = ?, `address` = ? WHERE `id` = ?";
$values = [$username, $hashedPassword, $email, $phoneNumber, $address, $id];
$result = $p->execute_query($sql_update, $values);

if ($result) {
    // Trả về dữ liệu nhân viên đã cập nhật
    $updatedEmployee = [
        'id' => $id,
        'username' => $username,
        'password' => $hashedPassword,
        'email' => $email,
        'phoneNumber' => $phoneNumber,
        'address' => $address
    ];
    echo json_encode(['status' => 'success', 'message' => 'Thông tin đã được cập nhật thành công.', 'updatedEmployee' => $updatedEmployee]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cập nhật thông tin không thành công.']);
}
