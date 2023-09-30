<?php
    include "access_token.php";
	$url = 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl';

	$access_token = $access_token; // check the mpesa_accesstoken.php file for this. No need to writing a new file here, just combine the code as in the tutorial.
	$shortCode = '4103521'; // provide the short code obtained from your test credentials

	/* This two files are provided in the project. */
	$confirmationUrl = 'https://springneighbourhood.com/pokearent/confirmation_url.php'; // path to your confirmation url. can be IP address that is publicly accessible or a url
	$validationUrl = 'https://springneighbourhood.com/pokearent/validation_url.php'; // path to your validation url. can be IP address that is publicly accessible or a url



	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header


	$curl_post_data = array(
	  //Fill in the request parameters with valid values
	  'ShortCode' => $shortCode,
	  'ResponseType' => 'Completed',
	  'ConfirmationURL' => $confirmationUrl,
	  'ValidationURL' => $validationUrl
	);

	$data_string = json_encode($curl_post_data);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

	$curl_response = curl_exec($curl);
	//echo $curl_response;
?>
