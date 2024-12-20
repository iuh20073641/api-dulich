<?php 
    class clsapi{
		
		private $conn;

		public function __construct(){
			$this->connect($this->conn);
		}

		// Thêm phương thức này để lấy kết nối
		public function get_connection()
		{
			return $this->conn;
		}

    	function connect(& $conn){
			$conn = mysqli_connect("localhost","root","","venture2");
			mysqli_set_charset($conn,'utf8');
			if(!$conn){
				echo "không kết nỗi được";
				exit();
			}else{
				mysqli_select_db($conn,"venture2");
				mysqli_query($conn,"SET NAMES UTF8");
				return $conn;
			}
		}

		function close_kn (& $conn){
			mysqli_close($conn);
		}

		public function xemRoom($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$name = $row["name"];
						$price = $row["price"];
						$discount = $row["discount"];
						$area = $row["area"];
						$address = $row["address"];
						$quantity = $row["quantity"];
						$adult = $row["adult"];
						$children = $row["children"];
						$description = $row["description"];
						$status = $row["status"];
						$removed = $row["removed"];

						$dulieu[]=array('id'=>$id, 'name'=>$name, 'price'=>$price, 'discount'=>$discount, 'area'=>$area, 'address'=>$address, 'quantity'=>$quantity, 'adult'=>$adult, 'children'=>$children, 'description'=>$description, 'status'=>$status, 'removed'=>$removed);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function features($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$name = $row["name"];
		
						$dulieu[]=array('id'=>$id, 'name'=>$name);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function facilities($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$name = $row["name"];
		
						$dulieu[]=array('id'=>$id, 'name'=>$name);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		// kiểm tra tài khoản khách hàng
		public function checkClient($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$nametk = $row["name"];
						$email = $row["email"];
						$phone = $row["phonenum"];
						$dob = $row["dob"];
						$address = $row["address"];
						$profile = $row["profile"];
		
						$dulieu[]=array('id'=>$id, 'nametk'=>$nametk, 'email'=>$email, 'phone'=>$phone, 'dob'=>$dob, 'address'=>$address, 'profile'=>$profile);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
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
		public function register($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			if($ketqua){
				echo"thành công";
			}else{
				echo"thất bại";
			}
		}

		// check user(email, phone)
		public function checkUser($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			if($ketqua){
				return $ketqua;
			}
		}

		// đăng nhập
		public function login($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$name = $row["name"];
						// $email = $row["email"];
						// $address = $row["address"];
						// $phonenu = $row["phonenumm"];
						// $dob = $row["dob"];
						// $email = $row["email"];
		
						$dulieu[]=array('id'=>$id, 'name'=>$name);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo json_encode([]);
			}
		}

		// Truy xuất thông tin team detail
		public function teamDetail($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$sr_no = $row["sr_no"];
						$name = $row["name"];
						$picture = $row["picture"];
		
						$dulieu[]=array('sr_no'=>$sr_no, 'name'=>$name, 'picture'=>$picture);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo json_encode([]);
			}
		}

		public function conTact($sql){
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

		public function add_contact($name, $email, $subject, $message){
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

		// Lây danh sách facilities của Admin
		public function Xemds_Facilities($sql)
		{
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link, $sql);
			$this->close_kn($conn);
			$i = mysqli_num_rows($ketqua);
			if ($i > 0) {
				while ($row = mysqli_fetch_array($ketqua)) {
					$id = $row["id"];
					$icon = $row["icon"];
					$name = $row["name"];
					$description = $row["description"];
					$dulieu[] = array('id' => $id, 'icon' => $icon, 'name' => $name, 'description' => $description);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			} else {
				echo "không có kết quả!";
			}
		}
		// Lây danh sách facilities của Admin
		public function Xemds_Features($sql)
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

		public function execute_query($sql, $values = [], $types = null)
		{
			// Kiểm tra kết nối
			if (!$this->conn) {
				$this->connect($this->conn);
			}

			// Chuẩn bị câu truy vấn
			$stmt = mysqli_prepare($this->conn, $sql);
			if ($stmt) {
				// Gán tham số cho câu truy vấn
				if ($types) {
					mysqli_stmt_bind_param($stmt, $types, ...$values);
				} else {
					$types = str_repeat('s', count($values));
					mysqli_stmt_bind_param($stmt, $types, ...$values);
				}

				// Thực thi câu truy vấn
				$result = mysqli_stmt_execute($stmt);

				// Kiểm tra loại câu truy vấn
				if (stripos($sql, 'SELECT') === 0) {
					// Truy vấn SELECT: lấy dữ liệu
					$result = mysqli_stmt_get_result($stmt);
					$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
					mysqli_stmt_close($stmt);
					return $data;
				} else {
					// Truy vấn khác: trả về true/false
					mysqli_stmt_close($stmt);
					return $result;
				}
			}
			return false;
		}

		public function selectRoomDetail($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$name = $row["name"];
						$price = $row["price"];
						$area = $row["area"];
						$quantity = $row["quantity"];
						$adult = $row["adult"];
						$children = $row["children"];
						$description = $row["description"];
						$status = $row["status"];
						$removed = $row["removed"];
						$dulieu[]=array('id'=>$id, 'name'=>$name, 'price'=>$price, 'area'=>$area, 'quantity'=>$quantity, 'adult'=>$adult, 'children'=>$children, 'description'=>$description, 'status'=>$status, 'removed'=>$removed);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}
		public function Xemds_nv($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$code = $row["username"];
				$tennhanvien = $row["tennhanvien"];
				$password = $row["password"];
				$email = $row["email"];
				$phoneNumber = $row["phoneNumber"];
				$address = $row["address"];
				$role = $row["role"];
				//$permissions = $row["permissions"];
				$dulieu[] = array('id' => $id, 'code' => $code, 'staffname' => $tennhanvien, 'password' => $password, 'email' => $email, 'phoneNumber' => $phoneNumber, 'address' => $address, 'role' => $role);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}
	public function Xemds_nv1($sql)
	{
		$link = $this->connect($conn);
		$ketqua = mysqli_query($link, $sql);
		$this->close_kn($conn);
		$i = mysqli_num_rows($ketqua);
		if ($i > 0) {
			while ($row = mysqli_fetch_array($ketqua)) {
				$id = $row["id"];
				$username = $row["username"];
				$tennhanvien = $row["tennhanvien"];
				$password = $row["password"];
				$email = $row["email"];
				$phoneNumber = $row["phoneNumber"];
				$address = $row["address"];
				$role = $row["role"];
				//$permissions = $row["permissions"];
				$dulieu[] = array('id' => $id, 'username' => $username, 'tennhanvien' => $tennhanvien, 'password' => $password, 'email' => $email, 'phoneNumber' => $phoneNumber, 'address' => $address, 'role' => $role);
			}
			header("content-Type:application/json; charset=UTF-8");
			echo json_encode($dulieu);
		} else {
			echo "không có kết quả!";
		}
	}
		// Lây danh sách nguoi dung 
		public function Xemds_Users($sql)
		{
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link, $sql);
			$this->close_kn($conn);
			$i = mysqli_num_rows($ketqua);
			if ($i > 0) {
				while ($row = mysqli_fetch_array($ketqua)) {
					$id = $row["id"];
					$name = $row["name"];
					$email = $row["email"];
					$phonenum = $row["phonenum"];
					$address = $row["address"];
					$dob = $row["dob"];
					$datetime = $row["datetime"];
					$profile = $row["profile"];
					$dulieu[] = array('id' => $id, 'name' => $name, 'email' => $email, 'phonenum' => $phonenum, 'address' => $address, 'dob' => $dob, 'datetime' => $datetime, 'profile' => $profile);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			} else {
				echo "không có kết quả!";
			}
		}

		public function xemTours($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$name = $row["name"];
						$style = $row["style"];
						$type = $row["type"];
						$price = $row["price"];
						$child_price_percen = $row["child_price_percen"];
						$toddlers_price_percen = $row["toddlers_price_percen"];
						$min_participant = $row["min_participant"];
						$max_participant = $row["max_participant"];
						$timeTour = $row["timetour"];
						$depart = $row["depart"];
						$departurelocation = $row["departurelocation"];
						$vehicle = $row["vehicle"];
						$description = $row["description"];
						$discount = $row["discount"];
						$itinerary = $row["itinerary"];
		
						$dulieu[]=array('id'=>$id, 'name'=>$name, 'style'=>$style, 'type'=>$type, 'price'=>$price, 'child_price_percen'=>$child_price_percen, 'toddlers_price_percen'=>$toddlers_price_percen, 'min_participant'=>$min_participant, 'max_participant'=>$max_participant, 'timeTour'=>$timeTour, 'depart'=>$depart, 
						'departurelocation'=>$departurelocation, 'vehicle'=>$vehicle, 'description'=>$description, 'discount'=>$discount, 
						'itinerary'=>$itinerary);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function seachTours($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id_tour"];
						$name = $row["name"];
						$style = $row["style"];
						$type = $row["type"];
						$price = $row["price"];
						$min_participant = $row["min_participant"];
						$max_participant = $row["max_participant"];
						$timeTour = $row["timetour"];
						$depart = $row["depart"];
						$departurelocation = $row["departurelocation"];
						$vehicle = $row["vehicle"];
						$description = $row["description"];
						$discount = $row["discount"];
						$itinerary = $row["itinerary"];
		
						$dulieu[]=array('id'=>$id, 'name'=>$name, 'style'=>$style, 'type'=>$type, 'price'=>$price, 'min_participant'=>$min_participant, 'max_participant'=>$max_participant, 'timeTour'=>$timeTour, 'depart'=>$depart, 
						'departurelocation'=>$departurelocation, 'vehicle'=>$vehicle, 'description'=>$description, 'discount'=>$discount, 
						'itinerary'=>$itinerary);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function tourSchedule($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$idTour = $row["id_tour"];
						$locations = $row["locations"];
						$date = $row["date"];
						$image = $row["image"];
						$schedule = $row["schedule"];
		
						$dulieu[]=array('id'=>$id, 'idTour'=>$idTour, 'date'=>$date, 'locations'=>$locations, 'image'=>$image, 'schedule'=>$schedule );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function tourRating($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$booking_id = $row["booking_id"];
						$tour_id = $row["tour_id"];
						$user_id = $row["user_id"];
						$user_name = $row["name"];
						$rating = $row["rating"];
						$review = $row["review"];
						$image_user = $row["profile"];
		
						$dulieu[]=array('id'=>$id, 'booking_id'=>$booking_id, 'tour_id'=>$tour_id, 'user_id'=>$user_id, 'user_name'=>$user_name, 'image_user'=>$image_user, 'rating'=>$rating, 'review'=>$review );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function allTourRating($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$booking_id = $row["booking_id"];
						$tour_id = $row["tour_id"];
						$tour_name = $row["tour_name"];
						$user_id = $row["user_id"];
						$user_name = $row["user_name"];
						$rating = $row["rating"];
						$review = $row["review"];
						$image_user = $row["profile"];
						$date = $row["datetime"];
		
						$dulieu[]=array('id'=>$id, 'booking_id'=>$booking_id, 'tour_id'=>$tour_id, 'tour_name'=>$tour_name, 'user_id'=>$user_id, 'user_name'=>$user_name, 'image_user'=>$image_user, 'rating'=>$rating, 'review'=>$review, 'date'=>$date );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function roomRating($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$booking_id = $row["booking_id"];
						$room_id = $row["room_id"];
						$user_id = $row["user_id"];
						$user_name = $row["name"];
						$rating = $row["rating"];
						$review = $row["review"];
						$image_user = $row["profile"];
		
						$dulieu[]=array('id'=>$id, 'booking_id'=>$booking_id, 'room_id'=>$room_id, 'user_id'=>$user_id, 'user_name'=>$user_name, 'image_user'=>$image_user, 'rating'=>$rating, 'review'=>$review );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function allRoomRating($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$booking_id = $row["booking_id"];
						$room_id = $row["room_id"];
						$room_name = $row["room_name"];
						$user_id = $row["user_id"];
						$user_name = $row["user_name"];
						$rating = $row["rating"];
						$review = $row["review"];
						$image_user = $row["profile"];
						$date = $row["datetime"];
		
						$dulieu[]=array('id'=>$id, 'booking_id'=>$booking_id, 'room_id'=>$room_id, 'room_name'=>$room_name, 'user_id'=>$user_id, 'user_name'=>$user_name, 'image_user'=>$image_user, 'rating'=>$rating, 'review'=>$review, 'date'=>$date );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function roomImage($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$room_id = $row["room_id"];
						$room_name = $row["name"];
						$image = $row["image"];
						$thumb = $row["thumb"];
		
						$dulieu[]=array('id'=>$id, 'room_id'=>$room_id, 'room_name'=>$room_name, 'image'=>$image, 'thumb'=>$thumb );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function tourImage($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$tour_id = $row["tour_id"];
						$image = $row["image"];
						$thumb = $row["thumb"];
		
						$dulieu[]=array('id'=>$id, 'tour_id'=>$tour_id, 'image'=>$image, 'thumb'=>$thumb );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function tourDepart($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$tour_id = $row["id_tour"];
						$day_depart = $row["day_depar"];
						$order = $row["orders"];
		
						$dulieu[]=array('id'=>$id, 'tour_id'=>$tour_id, 'day_depart'=>$day_depart, 'order'=>$order );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				header("Content-Type: application/json; charset=UTF-8");
    			echo json_encode([]); // Trả về mảng rỗng nếu không có dữ liệu
			}
		}

		public function user_queries($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$sr_no = $row["sr_no"];
						$name = $row["name"];
						$email = $row["email"];
						$subject = $row["subject"];
						$datetime = $row["datetime"];
						$seen = $row["seen"];
		
						$dulieu[]=array('sr_no'=>$sr_no, 'name'=>$name, 'email'=>$email, 'subject'=>$subject, 'datetime'=>$datetime, 'seen'=>$seen );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		// Lây danh sách tin tức của Admin
		public function News($sql)
		{
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link, $sql);
			$this->close_kn($conn);
			$i = mysqli_num_rows($ketqua);
			if ($i > 0) {
				$dulieu = [];
				while ($row = mysqli_fetch_array($ketqua)) {
					$id = $row["id"];
					$title = $row["title"];
					$summary = $row["summary"];
					$image = $row["image"];
					$content = $row["content"];
					$published_at = $row["published_at"];
					$dulieu[] = array('id' => $id, 'title' => $title, 'summary' => $summary, 'image' => $image, 'content' => $content, 'published_at' => $published_at);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			} else {
				echo json_encode(["error" => "No article found"]);
			}
		}

		public function getSettings($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$sr_no = $row["sr_no"];
						$site_title = $row["site_title"];
						$site_about = $row["site_about"];
		
						$dulieu[]=array('sr_no'=>$sr_no, 'site_title'=>$site_title, 'site_about'=>$site_about );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function carouselImage($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["sr_no"];
						$image = $row["image"];
		
						$dulieu[]=array('id'=>$id, 'image'=>$image );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function vahicleDepartTour($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$id_depart = $row["id_depart"];
						$day_depar = $row["day_depar"];
						$type = $row["type"];
						$departure_date = $row["departure_date"];
						$departure_time1 = $row["departure_time1"];
						$departure1 = $row["departure1"];
						$arrival_time1 = $row["arrival_time1"];
						$destination1 = $row["destination1"];
						$company1 = $row["company1"];
						$vehicle_number1 = $row["vehicle_number1"];
						$number_of_seats1 = $row["number_of_seats1"];
						$return_date = $row["return_date"];
						$departure_time2 = $row["departure_time2"];
						$departure2 = $row["departure2"];
						$arrival_time2 = $row["arrival_time2"];
						$destination2 = $row["destination2"];
						$company2 = $row["company2"];
						$vehicle_number2 = $row["vehicle_number2"];
						$number_of_seats2 = $row["number_of_seats2"];
		
						$dulieu[]=array('id'=>$id, 'id_depart'=>$id_depart, 'day_depar'=>$day_depar, 'type'=>$type, 'departure_date'=>$departure_date, 'departure_time1'=>$departure_time1, 
										'departure1'=>$departure1, 'arrival_time1'=>$arrival_time1, 'destination1'=>$destination1, 'company1'=>$company1
										, 'vehicle_number1'=>$vehicle_number1, 'number_of_seats1'=>$number_of_seats1, 'return_date'=>$return_date, 'departure_time2'=>$departure_time2 
										, 'departure2'=>$departure2, 'arrival_time2'=>$arrival_time2, 'destination2'=>$destination2, 'company2'=>$company2
										, 'vehicle_number2'=>$vehicle_number2, 'number_of_seats2'=>$number_of_seats2);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}

		public function getDepositHotel($sql){
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){		
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$id_depart = $row["id_depart"];
						$name_hotel = $row["name_hotel"];
						$address = $row["address"];
						$type = $row["type"];
						$quantity = $row["quantity"];
						$check_in = $row["check_in"];
						$check_out = $row["check_out"];
						$description = $row["description"];
		
						$dulieu[]=array('id'=>$id, 'id_depart'=>$id_depart, 'name_hotel'=>$name_hotel, 'address'=>$address, 'type'=>$type, 'quantity'=>$quantity, 'check_in'=>$check_in, 'check_out'=>$check_out, 'description'=>$description );
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"không có kết quả!";
			}
		}
		public function checkBookingRoom($sql) {
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link, $sql);
			$this->close_kn($conn);
		
			$dulieu = []; // Khởi tạo mảng rỗng mặc định
		
			if (mysqli_num_rows($ketqua) > 0) {		
				while ($row = mysqli_fetch_array($ketqua)) {
					$dulieu[] = [
						'booking_id' => $row["booking_id"],
						'room_id' => $row["room_id"],
				 		'check_in' => $row["check_in"],
						'check_out' => $row["check_out"]
					];
				}
				// header("Content-Type: application/json; charset=UTF-8");
				// echo json_encode(['status' => 'error', 'message' => 'phòng đã được đặt trong khoảng thời gian này']);
			} else {
				// Trả về JSON (mảng rỗng nếu không có dữ liệu)
				// header("Content-Type: application/json; charset=UTF-8");
				echo json_encode($dulieu);
				// echo json_encode(['status' => 'success', 'message' => 'phòng còn trống']);
			}
		
		}

		public function checkBookingTour($sql) {
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){		
				while($row=mysqli_fetch_array($ketqua)){
						$booking_id = $row["booking_id"];
						$departure_id = $row["departure_id"];
		
						$dulieu[]=array('booking_id'=>$booking_id, 'departure_id'=>$departure_id);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"[]";
			}
		
		}

		public function getAllHdv($sql) {
			$link = $this->connect($conn);
			$ketqua = mysqli_query($link,$sql);
			$this->close_kn ($conn);
			$i = mysqli_num_rows($ketqua);
			if($i>0){		
				while($row=mysqli_fetch_array($ketqua)){
						$id = $row["id"];
						$staffName = $row["tennhanvien"];
						$code = $row["username"];
		
						$dulieu[]=array('id'=>$id, 'code'=>$code, 'staffName'=>$staffName);
				}
				header("content-Type:application/json; charset=UTF-8");
				echo json_encode($dulieu);
			}else{
				echo"[]";
			}
		
		}

    }
?>