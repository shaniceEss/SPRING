<?php
session_start();
require_once "devcoder.php";

(new DotEnv(__DIR__ . '/.env'))->load();

ini_set('display_errors', 1);
class Mpesa
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }

    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    private function getAccessToken()
    {
        $url = getenv('MPESA_ENV') == 0
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_HTTPHEADER =>  array(
                    'Authorization: Basic ' . base64_encode(getenv('MPESA_CONSUMER_KEY') . ':' . getenv('MPESA_CONSUMER_SECRET'))
                ),
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
            )
        );
        $response = curl_exec($curl);

        if ($response === false) {
            return null;
        }

        curl_close($curl);
        $response = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if (isset($response['access_token'])) {
            return $response['access_token'];
        } else {
            return null;
        }
    }

    private function makeHttp($url, $body)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer " . $this->getAccessToken(), 'Content-Type application/json'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($body)
            )
        );
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }

    public function stkSimulate($phone_number, $amount, $account)
    {
        $response = $this->stkPay($phone_number, $amount, $account);
        return $response;
    }

    /**
     * receive lipa na mpesa express request data
     */
    private function stkPay($phone_number, $amount, $account)
    {
        $timestamp = date('YmdHis');
        $password = base64_encode(getenv('MPESA_STK_SHORTCODE') . getenv('MPESA_PASS_KEY') . $timestamp);

        $url = getenv('MPESA_ENV') == 0
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $body = array(
            'BusinessShortCode' => getenv('MPESA_STK_SHORTCODE'),
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone_number,
            'PartyB' => getenv('MPESA_STK_SHORTCODE'),
            'PhoneNumber' => $phone_number,
            'CallBackURL' => getenv('MPESA_TEST_URL') . '/callback.php',
            'AccountReference' =>  $account,
            'TransactionDesc' => 'Payment of Rental fee',
        );

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    public function registerURLS()
    {
        $url = getenv('MPESA_ENV') == 0
            ? 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'
            : 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

        $body = array(
            'ShortCode' => getenv('MPESA_SHORTCODE'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => getenv('MPESA_TEST_URL') . '/SPRING/confirmation.php',
            'ValidationURL' => getenv('MPESA_TEST_URL') . '/SPRING/validation.php',
        );

        $response = $this->makeHttp($url, $body);
        return $response;
    }

    public function validate()
    {
        $data = $_POST['data'];

        $logFile = 'callback_log.txt';
        $logData = date('Y-m-d H:i:s') . " - Received data: " . json_encode($data) . PHP_EOL;

        file_put_contents($logFile, $logData, FILE_APPEND);

        $response = [
            'ResultCode' => '0',
            'ResultDesc' => 'Accepted'
        ];

        header('Content-Type: application/json');

        echo json_encode($response);
    }

    public function confirmation()
    {
        $data = $_POST['data'];

        $logFile = 'callback_log.txt';
        $logData = date('Y-m-d H:i:s') . " - Received data: " . json_encode($data) . PHP_EOL;

        file_put_contents($logFile, $logData, FILE_APPEND);

        $response = [
            'ResultCode' => '0',
            'ResultDesc' => 'Success'
        ];

        header('Content-Type: application/json');

        echo json_encode($response);
    }
}
