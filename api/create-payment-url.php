<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header('Content-Type: application/json'); // Đảm bảo server trả về JSON

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $vnp_TmnCode = "R6ESP4JE"; // Mã website của bạn tại VNPay
    $vnp_HashSecret = "9BTC8526VSR4VDFO2ORFEXNUGXR9COGJ"; // Chuỗi bí mật do VNPay cung cấp

    // Lấy thông tin từ React gửi lên
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "http://localhost:3000/payment-result"; // Địa chỉ trả về kết quả
    $vnp_TxnRef = $data->orderId; // Mã đơn hàng
    $vnp_OrderInfo = "Thanh toán đơn hàng $vnp_TxnRef";
    $vnp_OrderType = "billpayment";
    $vnp_Amount = $data->amount * 100; // Tổng số tiền (VNPAY yêu cầu x100)
    $vnp_Locale = 'vn';
    $vnp_BankCode = '';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    );

    // Kiểm tra có mã ngân hàng không
    if (!empty($vnp_BankCode)) {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }

    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . $key . "=" . $value;
        } else {
            $hashdata .= $key . "=" . $value;
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }

    $response = array('paymentUrl' => $vnp_Url);
    echo json_encode($response);
?>