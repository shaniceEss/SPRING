<?php
  header("Content-Type: application/json");

  $response = '{
                  "ResultCode": 0, 
                  "ResultDesc": "Confirmation Received Successfully"
          }';

  // DATA
  $mpesaResponse = file_get_contents('php://input');

  $content = json_decode($mpesaResponse);

  $TransID = $content->TransID;
  $TransAmount = $content->TransAmount;
  $BillRefNumber = $content->BillRefNumber; #Clients A/C or InvoiceNumber
  
  #Database connection
  $servername = "localhost";
    $username = "springn1_house_rental_db_user";
    $password = "_FM19+W5SWEl";
    $database = "springn1_house_rental_db";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if(!empty($TransID)){
  //Insert tarnsactions to Mpesa_Transactions table
  $sql4 = $conn->query("INSERT INTO  payments  (tenant_id,amount,invoice)
	VALUES ('$BillRefNumber','$TransAmount','$TransID')");
}
  $conn = null;
  ?>
