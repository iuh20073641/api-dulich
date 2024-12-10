<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../../myclass/clsapi.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không được phép']);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

// Kiểm tra dữ liệu hợp lệ
if (empty($data->schedule) || empty($data->selectedDate)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
    exit();
}

$schedule = $data->schedule;
$selectedDate = $data->selectedDate;

// Kết nối cơ sở dữ liệu
$p = new clsapi();
$conn = $p->get_connection();

if (!$conn) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Không thể kết nối cơ sở dữ liệu', 'error' => mysqli_connect_error()]);
    exit();
}

try {
    // Bắt đầu transaction
    mysqli_begin_transaction($conn);

    // Thực hiện phân công lịch làm việc
    foreach ($schedule as $employeeId => $shifts) {
        foreach ($shifts as $shift => $value) {
            // Sử dụng prepared statement để bảo vệ khỏi SQL Injection
            $query = "INSERT INTO employee_schedule (employee_id, date, shift, value) 
                      VALUES (?, ?, ?, ?) 
                      ON DUPLICATE KEY UPDATE value = ?";

            // Chuẩn bị câu lệnh
            if ($stmt = mysqli_prepare($conn, $query)) {
                // Gán tham số vào câu lệnh chuẩn bị
                mysqli_stmt_bind_param($stmt, "isssi", $employeeId, $selectedDate, $shift, $value, $value);

                // Thực thi câu lệnh
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception('Không thể phân công lịch cho nhân viên');
                }
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception('Lỗi câu lệnh SQL');
            }
        }
    }

    // Commit transaction
    mysqli_commit($conn);

    http_response_code(200);
    echo json_encode(['status' => 'success', 'message' => 'Phân công lịch thành công']);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
} finally {
    mysqli_close($conn);
}
