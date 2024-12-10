<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);
$username = $data["username"] ?? null;
$tennhanvien = $data["tennhanvien"] ?? null;
$password = $data["password"] ?? null;
$email = $data["email"] ?? null;
$phoneNumber = $data["phoneNumber"] ?? null;
$address = $data["address"] ?? null;
$role = $data["role"] ?? null;
$permissions = $data["permissions"] ?? null;

// Kiểm tra các tham số
if (empty($username) || empty($tennhanvien) || empty($password) || empty($email) || empty($phoneNumber) || empty($address) || empty($role)) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
    exit();
}

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Kiểm tra xem tên đăng nhập đã tồn tại chưa
$check = $p->execute_query("SELECT * FROM `employees` WHERE `username` = ? LIMIT 1", [$username]);

if ($check && count($check) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại']);
} else {
    // Thêm nhân viên mới vào cơ sở dữ liệu
    $sql = "INSERT INTO `employees` (username, tennhanvien, password, email, phoneNumber, address, role, permissions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [$username, $tennhanvien, $hashedPassword, $email, $phoneNumber, $address, $role, $permissions];
    $result = $p->execute_query($sql, $params);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Tạo tài khoản thành công']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không thể tạo tài khoản']);
    }
}
