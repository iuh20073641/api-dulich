<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");

	include("../myclass/clsapi.php");

	$mail_phone = $_REQUEST["mail_phone"];
    $pass = $_REQUEST["pass"];

	$p = new clsapi();
	$check = $p->checkUser("SELECT * FROM `user_cred` WHERE `email`='$mail_phone' OR `phonenum`='$mail_phone' LIMIT 1");
	if(mysqli_num_rows($check)>0){
		$u_exist_fectch = mysqli_fetch_assoc($check);
		if (password_verify($pass, $u_exist_fectch['password'])) {
			$i = new clsapi();
			$i->login("SELECT * FROM `user_cred` WHERE `email`='$mail_phone' OR `phonenum`='$mail_phone' LIMIT 1");
        } else {
			echo json_encode(['status' => 'Sai mật khẩu']);
        }
	}else{
		echo json_encode(['status' => 'Tài khoản không tồn tại']);
	}
	
	// $p->features("SELECT * FROM `user_cred` WHERE `email`='$mail' OR `phonenum`='$phone' LIMIT 1");
	
?>