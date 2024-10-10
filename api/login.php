<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$mail_phone = $_REQUEST["mail_phone"];
	$pass = $_REQUEST["pass"];

	$p = new clsapi();
	$check = $p->execute_query("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", [$mail_phone, $mail_phone]);

	if (count($check) > 0) {
		$u_exist_fectch = $check[0];
		if (password_verify($pass, $u_exist_fectch['password'])) {
			// Truy vấn đầy đủ thông tin người dùng
			$user_query = "SELECT id, name, email, phonenum, address, dob, datetime, profile FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1";
			$user_result = $p->execute_query($user_query, [$mail_phone, $mail_phone]);
			if ($user_result) {
				$user_data = $user_result[0];
				// Trả về dữ liệu dưới dạng JSON
				echo json_encode($user_data);
			} else {
				echo json_encode(['status' => 'Không thể lấy thông tin người dùng']);
			}
		} else {
			echo json_encode(['status' => 'Sai mật khẩu']);
		}
	} else {
		echo json_encode(['status' => 'Tài khoản không tồn tại']);
	}
?>