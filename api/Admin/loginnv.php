<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Đọc và giải mã dữ liệu JSON từ yêu cầu POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
	echo json_encode(['status' => 'error', 'message' => 'Dữ liệu JSON không hợp lệ.']);
	exit();
}

// Lấy dữ liệu từ yêu cầu
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

// Kiểm tra dữ liệu đầu vào
if (empty($username) || empty($password)) {
	echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập và mật khẩu là bắt buộc.']);
	exit();
}

if (strlen($username) < 3 || strlen($password) < 5) {
	echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập hoặc mật khẩu quá ngắn.']);
	exit();
}

// Truy vấn thông tin nhân viên từ cơ sở dữ liệu
$sql_select = "SELECT `id`, `username`, `password`, `role`, `email`, `phoneNumber`, `address` FROM `employees` WHERE `username` = ?";
$values = [$username];
$result = $p->execute_query($sql_select, $values);

if ($result && count($result) > 0) {
	$employee = $result[0];
	// Kiểm tra mật khẩu
	if (password_verify($password, $employee['password'])) {
		// Tạo token hoặc session cho nhân viên
		$token = bin2hex(random_bytes(16)); // Giả sử tạo token ngẫu nhiên
		echo json_encode([
			'status' => 'success',
			'data' => [
				'id' => $employee['id'],
				'role' => $employee['role'],
				'token' => $token,
				'username' => $employee['username'],
				'password' => $employee['password'], // Đảm bảo trả về trường password
				'email' => $employee['email'],
				'phoneNumber' => $employee['phoneNumber'],
				'address' => $employee['address']
			]
		]);
	} else {
		echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không chính xác.']);
	}
} else {
	echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập không tồn tại.']);
}
