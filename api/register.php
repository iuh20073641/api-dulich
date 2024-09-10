<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$name = $_REQUEST["name"];
	$mail = $_REQUEST["mail"];
	$phone = $_REQUEST["phone"];
	$image = $_REQUEST["image"];
	$address = $_REQUEST["address"];
	$date = $_REQUEST["birthDate"];
	$pass = $_REQUEST["pass"];

	// Định dạng ngày tháng
	$ngayThangMoi = date("Y-m-d", strtotime($date));


	// Encrypt password
    $enc_pass = password_hash($pass, PASSWORD_BCRYPT);
	$p = new clsapi();
	// $kq = $p->checkClient("SELECT id, email, phonenum FROM `user_cred` WHERE `email`='$mail' OR `phonenum`='$phone' LIMIT 1");
	// if($kq==0){
		if(!empty($name) && !empty($mail) && !empty($phone) && !empty($image) && !empty($address) && !empty($date) && !empty($pass)){
			// $i = new clsapi();
			$p->register("INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `dob`,`profile`, `password`)
				VALUES ('$name', '$mail', '$address', '$phone', '$date', '$image', '$enc_pass')");
		}else{
			echo json_encode(['status' => 'success', 'message' => 'không đủ thông tin']);
			// echo "alert('thiếu thông tin')";
		}
	// }else{
	// 	echo json_encode(['status' => 'Tài khoản đã tồn tại']);
	// }
?>