<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include("../../myclass/clsapi.php");

$p = new clsapi();

// Xử lý yêu cầu OPTIONS trước khi xử lý yêu cầu PUT
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lấy dữ liệu từ yêu cầu PUT
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->title) &&
    !empty($data->summary) &&
    !empty($data->content) &&
    !empty($data->image) &&
    !empty($data->published_at) &&
    isset($_GET['id'])
) {
    $id = $_GET['id'];
    $title = $data->title;
    $summary = $data->summary;
    $content = $data->content;
    $image = $data->image;
    $published_at = $data->published_at;

    // Cập nhật bài viết trong cơ sở dữ liệu
    $sql = "UPDATE news SET title = ?, summary = ?, content = ?, image = ?, published_at = ? WHERE id = ?";
    $values = [$title, $summary, $content, $image, $published_at, $id];
    $types = 'sssssi'; // 5 tham số kiểu chuỗi và 1 tham số kiểu số nguyên

    $result = $p->execute_query($sql, $values, $types);

    if ($result) {
        // Trả về phản hồi thành công
        http_response_code(200);
        echo json_encode(array("status" => "success", "message" => "Bài viết đã được cập nhật thành công."));
    } else {
        // Trả về phản hồi lỗi
        http_response_code(503);
        echo json_encode(array("status" => "error", "message" => "Không thể cập nhật bài viết."));
    }
} else {
    // Trả về phản hồi lỗi nếu dữ liệu không đầy đủ
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "Dữ liệu không đầy đủ."));
}