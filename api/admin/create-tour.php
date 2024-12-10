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
    $name = $data['name'] ?? null;
    $type = $data['type'] ?? null;
    $style = $data['style'] ?? null;
    $min_participant = $data['min_participant'] ?? null;
    $max_participant = $data['max_participant'] ?? null;
    $price = $data['price'] ?? null;
    $price_toddlers = $data['price_toddlers'] ?? null;
    $price_child = $data['price_child'] ?? null;
    $description = $data['description'] ?? null;
    $timetour = $data['timetour'] ?? null;
    $depart = $data['depart'] ??  null;
    $departurelocation = $data['departurelocation'] ?? null;
    $discount = $data['discount'] ??  null;
    $itinerary = $data['itinerary'] ??  null;
    $vehicle = $data['vehicle'] ??  null;

    // Kiểm tra các tham số
    if (!$name || !$type || !$min_participant || !$max_participant || !$price || !$description || !$timetour || !$depart || !$departurelocation || !$itinerary || !$vehicle || !$style || !$price_toddlers || !$price_child) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết']);
        exit();
    }

    // Kiểm tra tour đã tồn tại chưa
    $sql_check = "SELECT * FROM `tours` WHERE `name` = ?";
    $existing_tour = $p->execute_query($sql_check, [$name]);

    if ($existing_tour && count($existing_tour) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tour đã tồn tại.']);
        exit();
    }

    // Thêm tour mới nếu chưa tồn tại
    $sql_insert = "INSERT INTO `tours`(`name`, `style`, `type`, `price`, `child_price_percen`, `toddlers_price_percen`, `min_participant`, `max_participant`, `description`, `timetour`, `depart`, `departurelocation`, `discount`, `itinerary`, `vehicle`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $values = [$name, $style, $type, $price, $price_child, $price_toddlers, $min_participant , $max_participant , $description, $timetour, $depart, $departurelocation, $discount, $itinerary, $vehicle];
    $result = $p->execute_query($sql_insert, $values);

    if ($result) {
        
        echo json_encode(['status' => 'success', 'message' => 'Tour đã được thêm thành công.']);
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Thêm tour không thành công.']);
    }
?>