<?php
header("Content-Type: application/json; charset=UTF-8");

include("../myclass/clsapi1.php");

$p = new clsapi();

$sql = "SELECT u.id, u.username, r.role_name FROM users u JOIN roles r ON u.role_id = r.id";
$result = $p->fetch_all($sql);

if ($result) {
    echo json_encode(['status' => 'success', 'data' => $result]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve users.']);
}
