<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=UTF-8");

    include("../../myclass/clsapi.php");

    $p = new clsapi();

    // Lấy tham số từ yêu cầu GET
    $booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : '';
    
    // Thực hiện truy vấn SQL
    $query = "SELECT * FROM participants WHERE booking_id = $booking_id";

    // Chuẩn bị và thực thi truy vấn
    // $search_param = "%$order_id%";
    $result = $p->execute_query($query, $booking_id);

    // Trả về kết quả dưới dạng JSON
    echo json_encode($result);
?>