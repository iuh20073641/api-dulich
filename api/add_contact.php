<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include("../myclass/clsapi.php");

// Khởi tạo đối tượng clsapi
$p = new clsapi();

// Lấy dữ liệu từ GET request
$name = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Làm sạch dữ liệu đầu vào
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Gọi phương thức add_contact để thêm dữ liệu vào cơ sở dữ liệu
$p->add_contact($name, $email, $subject, $message);
