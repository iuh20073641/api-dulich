<?php
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include("../../myclass/clsapi.php");

    // Tạo một thể hiện của lớp
    $p = new clsapi();

    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra các tham số cần thiết
        if ($data === null) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số']);
        } else {

            // Lấy dữ liệu từ yêu cầu
            $id_tour = $data['id_tour'] ?? null;
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
            if (!$id_tour || !$name || !$type || !$min_participant || !$max_participant || !$price || !$description || !$timetour || !$depart || !$departurelocation || !$itinerary || !$vehicle || !$style || !$price_toddlers || !$price_child) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin cần thiết']);
                exit();
            }

            $pre_q = "UPDATE `tours` SET `name`=?, `style` = ?, `type` = ?, `price` = ?, `child_price_percen` = ?, `toddlers_price_percen` = ?, `min_participant` = ?, `max_participant` = ?, `description` = ?, `timetour` = ?, `depart` = ?, `departurelocation` = ?, `discount` = ?, `itinerary` = ?, `vehicle` = ? WHERE `id`=?";
            $pre_v = [$name, $style, $type, $price, $price_child, $price_toddlers, $min_participant , $max_participant , $description, $timetour, $depart, $departurelocation, $discount, $itinerary, $vehicle, $id_tour];
            $pre_res = $p->execute_query($pre_q, $pre_v, 'sssiiiiisississi');


            if ($pre_res) {
                echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Cập nhật không thành công']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    }
?>