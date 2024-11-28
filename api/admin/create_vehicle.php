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

    // Lấy dữ liệu từ yêu cầu
    $id_depart = $data['id_depart'] ?? null;
    $type = $data['type'] ?? null;
    $departure_date = $data['departure_date'] ?? null;
    $departure_time1 = $data['departure_time1'] ?? null;
    $departure1 = $data['departure1'] ?? null;
    $arrival_time1 = $data['arrival_time1'] ?? null;
    $destination1 = $data['destination1'] ?? null;
    $company1 = $data['company1'] ??  null;
    $vehicle_number1 = $data['vehicle_number1'] ?? null;
    $number_of_seats1 = $data['number_of_seats1'] ??  null;
    $return_date = $data['return_date'] ??  null;
    $departure_time2 = $data['departure_time2'] ??  null;
    $departure2 = $data['departure2'] ??  null;
    $arrival_time2 = $data['arrival_time2'] ??  null;
    $destination2 = $data['destination2'] ??  null;
    $company2 = $data['company2'] ??  null;
    $vehicle_number2 = $data['vehicle_number2'] ??  null;
    $number_of_seats2 = $data['number_of_seats2'] ??  null;

    // Kiểm tra các tham số
    if (!$id_depart || !$type || !$departure_date || !$departure_time1 || !$departure1 || !$arrival_time1 || !$destination1 || !$company1 || !$vehicle_number1 || !$number_of_seats1 || !$return_date || !$departure_time2 || !$departure2 || !$arrival_time2 || !$destination2 || !$company2 || !$vehicle_number2 || !$number_of_seats2) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu hoặc không hợp lệ các tham số.']);
        exit();
    }

     // Kiểm tra ngày tổ chứa tour 
     $sql_check_date = "SELECT day_depar FROM `departure_time` WHERE `id` = ? ";
     $existing_date = $p->execute_query($sql_check_date, [$id_depart]);
 
     if ($existing_date) {
        $day_depar = new DateTime($existing_date[0]['day_depar']);
        $departure_date_obj = new DateTime($departure_date);
        $return_date_obj = new DateTime($return_date);
    
        if ($departure_date_obj < $day_depar || $return_date_obj < $day_depar) {
            echo json_encode(['status' => 'warning', 'message' => 'Ngày đi hoặc ngày về không hợp lệ so với ngày tổ chức tour.']);
            exit();
        }
    }

    if (new DateTime($departure_date) > new DateTime($return_date)) {
        echo json_encode(['status' => 'warning', 'message' => 'Ngày khởi hành không thể lớn hơn ngày trở về']);
        exit();
    }

    // Kiểm tra phương tiện đã tồn tại chưa
    $sql_check = "SELECT * FROM `vehicle` WHERE `id_depart` = ? AND ((`vehicle_number1` = ? AND `departure_date` = ? ) OR (`vehicle_number2` = ? AND `return_date` = ?))";
    $existing_tour = $p->execute_query($sql_check, [$id_depart, $vehicle_number1, $departure_date, $vehicle_number2, $return_date]);

    if ($existing_tour && count($existing_tour) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Phương tiện đã tồn tại.']);
        exit();
    }

    // Thêm phương tiện mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO `vehicle`(`id_depart`, `type`, `departure_date`, `departure_time1`, `departure1`, `arrival_time1`, `destination1`, `company1`, `vehicle_number1`, `number_of_seats1`, `return_date`, `departure_time2`,`departure2`, `arrival_time2`, `destination2`, `company2`, `vehicle_number2`, `number_of_seats2`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $values = [$id_depart, $type , $departure_date , $departure_time1 , $departure1, $arrival_time1, $destination1, $company1, $vehicle_number1, $number_of_seats1, $return_date, $departure_time2, $departure2, $arrival_time2, $destination2, $company2, $vehicle_number2, $number_of_seats2];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        
        echo json_encode(['status' => 'success', 'message' => 'Phương tiện đã được thêm thành công.']);
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Thêm phương tiện không thành công.']);
    }
?>