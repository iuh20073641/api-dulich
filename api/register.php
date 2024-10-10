<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	// Lấy dữ liệu từ yêu cầu
	$name = $_REQUEST["name"] ?? null;
	$mail = $_REQUEST["mail"] ?? null;
	$phone = $_REQUEST["phone"] ?? null;
	$address = $_REQUEST["address"] ?? null;
	$date = $_REQUEST["birthDate"] ?? null;
	$pass = $_REQUEST["pass"] ?? null;
	$image = "";

	// Kiểm tra dữ liệu đầu vào
	if ($mail === null || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
		exit();
	}

	if ($pass === null || strlen($pass) < 6) {
		echo json_encode(['status' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự']);
		exit();
	}

	// Định dạng ngày tháng
	$ngayThangMoi = date("Y-m-d", strtotime($date));

	// Encrypt password
	$enc_pass = password_hash($pass, PASSWORD_BCRYPT);

	// Gọi endpoint để tải lên hình ảnh
	if (isset($_FILES["image"])) {
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
		} else {
			echo json_encode(["status" => "error", "message" => $response_data["message"] ?? 'Lỗi tải lên hình ảnh']);
			exit();
		}
	}

	// Lưu dữ liệu người dùng vào cơ sở dữ liệu với MySQLi
	$p = new clsapi();
	if (!empty($name) && !empty($mail) && !empty($phone) && !empty($image) && !empty($address) && !empty($date) && !empty($pass)) {
		// Chuẩn bị câu truy vấn MySQLi
		$stmt = $p->get_connection()->prepare("INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `dob`, `profile`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?)");

		// Gán giá trị và thực thi câu truy vấn
		$stmt->bind_param("sssssss", $name, $mail, $address, $phone, $ngayThangMoi, $image, $enc_pass);
		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Người dùng đã được tạo thành công.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Không thể tạo người dùng.']);
		}

		// Đóng câu lệnh
		$stmt->close();
	} else {
		echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin bắt buộc.']);
	}
?>