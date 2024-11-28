<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
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

    $depart_id = $data["depart_id"] ?? null;
    $name_hotel = $data["name_hotel"] ?? null;
    $address = $data["address"] ?? null;
    $type = $data["type"] ?? null;
    $quantity = $data["quantity"] ?? null;
    $check_in = $data["check_in"] ?? null;
    $check_out = $data["check_out"] ?? null;
    $description = $data["description"] ?? null;

    // Kiểm tra loại phòng đã tồn tại chưa
    $sql_check = "SELECT * FROM `deposit_hotel` WHERE `type` = ?";
    $existing = $p->execute_query($sql_check, [$type]);

    if (!empty($existing)) {
       
        echo json_encode(['status' => 'error', 'message' => 'Loại phòng đã tồn tại.']);
        exit();
    }

    // Kiểm tra ngày đặt 
    $sql_check_date = "SELECT day_depar FROM `departure_time` WHERE `id` = ? ";
    $existing_date = $p->execute_query($sql_check_date, [$depart_id]);

    if ($existing_date) {
       $day_depar = new DateTime($existing_date[0]['day_depar']);
       $check_in_obj = new DateTime($check_in);
       $check_out_obj = new DateTime($check_out);
   
       if ($check_in_obj < $day_depar || $check_out_obj < $day_depar) {
           echo json_encode(['status' => 'warning', 'message' => 'Ngày đặt hoặc ngày trả không hợp lệ so với ngày tổ chức tour.']);
           exit();
       }
   }

   if (new DateTime($check_in) > new DateTime($check_out)) {
       echo json_encode(['status' => 'warning', 'message' => 'Ngày đặt phòng không thể lớn hơn ngày trả phòng']);
       exit();
   }


    // Thêm loại phòng mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO `deposit_hotel`(`id_depart`,`name_hotel`,`address`,`type`,`quantity`,`check_in`,`check_out`,`description`) VALUES (?,?,?,?,?,?,?,?)";
    $values = [$depart_id,$name_hotel,$address,$type,$quantity,$check_in,$check_out,$description];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
       
        echo json_encode(['status' => 'success', 'message' => ' Tạo mới thành công.']);
    } else {
       
        echo json_encode(['status' => 'error', 'message' => 'Tạo mới không thành công.']);
    }
?>