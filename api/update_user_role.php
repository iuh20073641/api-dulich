<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, PATCH");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include("../myclass/clsapi1.php");

$p = new clsapi();

// Get PUT request body
parse_str(file_get_contents("php://input"), $put_vars);

if (isset($put_vars['user_id']) && isset($put_vars['role_id'])) {
    $user_id = $put_vars['user_id'];
    $role_id = $put_vars['role_id'];

    // SQL query to update the role of the user
    $sql = "UPDATE users SET role_id = ? WHERE id = ?";

    // Execute the query
    $result = $p->update_user_role($user_id, $role_id, $sql);

    echo json_encode($result);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
}
