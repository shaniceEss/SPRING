   <?php
    include('db_connect.php');

    $data = $_POST['data'];

    if ($data["stkCallback"]["ResultCode"] == 0) {
        $transaction_date = $data["stkCallback"]["CallbackMetadata"]["Item"][3]["Value"];
        $merchant_request_id = $data["stkCallback"]["MerchantRequestID"];
        $checkout_request_id = $data["stkCallback"]["CheckoutRequestID"];
        $result_code = $data["stkCallback"]["ResultCode"];
        $result_description = $data["stkCallback"]["ResultDesc"];
        $amount = $data["stkCallback"]["CallbackMetadata"]["Item"][0]["Value"];
        $mpesa_receipt_number = $data["stkCallback"]["CallbackMetadata"]["Item"][1]["Value"];
        $phone_number = $data["stkCallback"]["CallbackMetadata"]["Item"][4]["Value"];
        $transaction_date = strval(date("Y-m-d H:i:s", strtotime($transaction_date)));
        //make query to store this transacion data into database here

        $result = $conn->query("SELECT * FROM tenants where contact = $phoner_number");
        $tenant = $result->fetch_object();

        if ($tenant) {
            $payment_data = " tenant_id = '$tenant->id'";
            $payment_data .= "amount  = '$amount'";
            $payment_data .= "invoice  = '$mpesa_receipt_number'";

            $db = $conn->query("INSERT INTO payments set " . $payment_data);

            if ($db) {
                $response_data = array('ResultCode' => $result_code, 'ResultDesc' => 'Success');
                header("Content-Type: application/json");
                echo json_encode($response_data);
            }
        }
    }
