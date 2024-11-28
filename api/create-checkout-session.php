<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// create-checkout-session.php
require '../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51Q8m79Rqz8axCXq0RZChmZ3GE9Q65hpLHXwd64XbT7qmPkhx7Ev847fypL2npgmVZy2M6wpMpAgIKjzs8pA67a3W00ROV28eOD');

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$amount = $data['amount'];
// $name = $data['name'];
// $user_id = $data['user_id'];
// $id_tour = $data['id_tour'];
// $depar_id = $data['depar_id'];
// $participant = $data['participant'];
// $price_tour = $data['price_tour'];
// $name_user = $data['name_user'];
// $phone = $data['phone'];
// $address = $data['address'];
// $tour_name = $data['tour_name'];
$url_success = $data['success_url'];


// $name_tour = $data['name_tour'];

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'vnd',
                'product_data' => [
                    'name' => 'thanh toán',
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $url_success,
        'cancel_url' => 'http://localhost:3000/cancel',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}