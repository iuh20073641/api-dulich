<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Lấy tham số từ yêu cầu GET
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
    $phonenum = isset($_GET['phonenum']) ? $_GET['phonenum'] : '';
    $user_name = isset($_GET['user_name']) ? $_GET['user_name'] : '';

    // Thực hiện truy vấn SQL
    $query = "SELECT bo.*, bd.*, dt.*, t.discount FROM `booking_order_tour` bo
        INNER JOIN `booking_detail_tour` bd ON bo.booking_id = bd.booking_id
        LEFT JOIN `departure_time` dt ON bo.departure_id = dt.id
        LEFT JOIN `tours` t ON bo.tour_id = t.id
        AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
        ";

    // Chuẩn bị và thực thi truy vấn
    $search_param = "%$order_id%";
    $result = $p->execute_query($query, [$search_param, $search_param, $search_param]);

    // Trả về kết quả dưới dạng JSON
    echo json_encode($result);
?>