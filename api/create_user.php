<?php
header("Content-Type: application/json; charset=UTF-8");

include("../myclass/clsapi1.php");

$p = new clsapi();

$username = $_POST['username'];
$password = $_POST['password'];
$role_id = $_POST['role_id'];

$sql = "INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)";
$values = [$username, $password, $role_id];
$result = $p->execute_query($sql, $values);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'User created successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create user.']);
}
