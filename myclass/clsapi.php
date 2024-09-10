<?php
class clsapi
{
	function connect(&$conn)
	{
		$conn = mysqli_connect("localhost:3307", "root", "", "venture");
		mysqli_set_charset($conn, 'utf8');
		if (!$conn) {
			echo "không kết nỗi được";
			exit();
		} else {
			mysqli_select_db($conn, "venture");
			mysqli_query($conn, "SET NAMES UTF8");
			return $conn;
		}
	}

	function close_kn(&$conn)
	{
		mysqli_close($conn);
	}

	public function xemRoom($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$name = $row["name"];
				$price = $row["price"];
				$area = $row["area"];
				$quantity = $row["quantity"];
				$adult = $row["adult"];
				$children = $row["children"];
				$description = $row["area"];
				$status = $row["area"];
				$removed = $row["area"];
				$dulieu[] = array('id' => $id, 'name' => $name, 'price' => $price, 'area' => $area, 'quantity' => $quantity, 'adult' => $adult, 'children' => $children, 'description' => $description, 'status' => $status, 'removed' => $removed);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}

	public function features($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["sr_no"];
				$name = $row["name"];

				$dulieu[] = array('id' => $id, 'name' => $name);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}

	public function facilities($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$name = $row["name"];

				$dulieu[] = array('id' => $id, 'name' => $name);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}

	// kiểm tra tài khoản khách hàng
	public function checkClient($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$email = $row["email"];
				$phone = $row["phonenum"];

				$dulieu[] = array('id' => $id, 'email' => $email, 'phone' => $phone);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo json_encode([]);
		}
	}

	// public function checkClient($sql){
	// 	$link = $this->connect($conn);
	// 	$ketqua = mysqli_query($link,$sql);
	// 	$this->close_kn ($conn);
	// 	$i = mysqli_num_rows($ketqua);
	// 	return $i;
	// }

	// đăng ký
	public function register($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		if ($ketqua) {
			echo "thành công";
		} else {
			echo "thất bại";
		}
	}

	// check user(email, phone)
	public function checkUser($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		if ($ketqua) {
			return $ketqua;
		}
	}

	// đăng nhập
	public function login($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$name = $row["name"];
				// $email = $row["email"];
				// $address = $row["address"];
				// $phonenu = $row["phonenumm"];
				// $dob = $row["dob"];
				// $email = $row["email"];

				$dulieu[] = array('id' => $id, 'name' => $name);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo json_encode([]);
		}
	}

	// Truy xuất thông tin team detail
	public function teamDetail($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$sr_no = $row["sr_no"];
				$name = $row["name"];
				$picture = $row["picture"];

				$dulieu[] = array('sr_no' => $sr_no, 'name' => $name, 'picture' => $picture);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo json_encode([]);
		}
	}
	//Xem phan hoi (user queries)
	public function conTact($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$sr_no = $row["sr_no"];
				$name = $row["name"];
				$email = $row["email"];
				$subject = $row["subject"];
				$datetime = $row["datetime"];
				$seen = $row["seen"];
				$dulieu[] = array('id' => $sr_no, 'name' => $name, 'email' => $email, 'subject' => $subject, 'datetime' => $datetime, 'seen' => $seen);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}
	public function add_contact($name, $email, $subject, $message)
	{
		// Kết nối cơ sở dữ liệu
		$link = $this->connect($conn);

		// Chuẩn bị câu lệnh SQL
		$sql = "INSERT INTO user_queries(name, email, subject, message) VALUES (?, ?, ?, ?)";
		$stmt = mysqli_prepare($link, $sql);

		if ($stmt) {
			// Liên kết các tham số
			mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);

			// Thực thi câu lệnh
			$result = mysqli_stmt_execute($stmt);

			if ($result) {
				echo json_encode(['status' => 'success', 'message' => 'Thành công']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Thất bại: ' . mysqli_stmt_error($stmt)]);
			}

			// Đóng câu lệnh
			mysqli_stmt_close($stmt);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Lỗi trong quá trình chuẩn bị câu truy vấn']);
		}

		// Đóng kết nối cơ sở dữ liệu
		$this->close_kn($conn);
	}
	public function conTact_detail($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$sr_no = $row["sr_no"];
				$address = $row["address"];
				$gmap = $row["gmap"];
				$pn1 = $row["pn1"];
				$pn2 = $row["pn2"];
				$email = $row["email"];
				$tw = $row["tw"];
				$fb = $row["fb"];
				$insta = $row["insta"];
				$jframe = $row["jframe"];
				$dulieu[] = array('sr_no' => $sr_no, 'address' => $address, 'gmap' => $gmap, 'pn1' => $pn1, 'pn2' => $pn2, 'email' => $email, 'tw' => $tw, 'fb' => $fb, 'insta' => $insta, 'jframe' => $jframe);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}
}
