<?php
include 'mpesa_class.php';

if (isset($_POST['Body'])) {
    $jsonData = $_POST['Body'];
    $data = json_decode($jsonData, true);
    $phone_number = $data['phone_number'];
    $amount = $data['amount'];
    $account = $data['house_no'];

    $mpesa = new Mpesa();
    $response = $mpesa->stkSimulate($phone_number, $amount, $account);

    "Response : $response";
}
