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

    $now_day = date('Y-m-d');

    // Thực hiện truy vấn SQL
    $query = "SELECT bo.*, bd.* FROM `booking_order_tour` bo
        INNER JOIN `booking_detail_tour` bd ON bo.booking_id = bd.booking_id
        WHERE bo.booking_status = 'booker' AND bo.arrival = 1 AND bo.refund is null
        AND dt.check_in >= $now_day
        AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
        ORDER BY bo.booking_id DESC";

    // Chuẩn bị và thực thi truy vấn
    $search_param = "%$order_id%";
    $result = $p->execute_query($query, [$search_param, $search_param, $search_param]);

    // Trả về kết quả dưới dạng JSON
    echo json_encode($result);
?>