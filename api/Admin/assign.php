<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include("../../myclass/clsapi.php");

$p = new clsapi();

if (isset($_POST['assign_room'])) {
    // Lọc dữ liệu từ $_POST
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` bo INNER JOIN `booking_details` bd
              ON bo.booking_id = bd.booking_id
              SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ?
              WHERE bo.booking_id = ?";

    $values = [1, 0, $frm_data['room_no'], $frm_data['booking_id']];

    $res = $p->execute_query($query, $values, 'iisi');

    echo json_encode(['success' => $res == 2]);
}

// Hàm lọc dữ liệu
function filteration($data)
{
    foreach ($data as $key => $value) {
        $data[$key] = htmlspecialchars(strip_tags($value));
    }
    return $data;
}
