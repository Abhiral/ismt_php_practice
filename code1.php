<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Exception;

class NabilBankController extends Controller
{
    private $orderType;
    private $autoCaptureTime;
    private $additionalParams;

    public function __construct()
    {
        $this->orderType = 'Purchase'; // Assuming default value
        $this->autoCaptureTime = 60; // Assuming a default value
        $this->additionalParams = ['param1' => 'value1', 'param2' => 'value2']; // Example additional parameters
    }

    public function checkConnection()
    {
      //  $host = '91.227.244.57'; // Update with the desired host
        $port = 8444;
       // $url = 'https://nabiltest.compassplus.com' . $port;
        $url = 'https://nabiltest.compassplus.com'; // Update with the desired URL
        // Update with the desired port

        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url . ':' . $port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        curl_exec($ch);

        // Check for any errors
        if (curl_errno($ch)) {
            echo 'Error occurred while connecting to ' . $url . ':' . $port . ': ' . curl_error($ch);
        } else {
            echo 'Connection to ' . $url . ':' . $port . ' is successful.';
        }

        // Close cURL session
        curl_close($ch);
    }
    public function createOrder()
    {
        // Build the XML request
        $xmlRequest = $this->generateCreateOrderXML();

       
        $nabilBankUrl = 'https://nabiltest.compassplus.com:8444/Exec'; // Replace with the actual Nabil Bank URL

        // Send XML request to Nabil Bank PG using HTTPS (POST method).
        $response = $this->sendRequestToNabilBank($nabilBankUrl, $xmlRequest);

        // Handle the response and redirect the customer as needed.
        // ...

        return response()->json(['message' => 'Order Created']);
    }

    public function sendTransactionRequest(Request $request)
    {
        $nabilBankUrl = 'https://www.example.com/NabilBankService';
        $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
                       <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://www.example.com/NabilBankService">
                           <SOAP-ENV:Body>
                               <ns1:executeTransaction>
                                   <ns1:transactionType>YOUR_TRANSACTION_TYPE</ns1:transactionType>
                                   <ns1:amount>YOUR_AMOUNT</ns1:amount>
                                   <!-- Add more transaction parameters if needed -->
                               </ns1:executeTransaction>
                           </SOAP-ENV:Body>
                       </SOAP-ENV:Envelope>';

        $response = $this->sendRequestToNabilBank($nabilBankUrl, $xmlRequest);

       

        return response()->json(['response' => $response]);
    }

    // Send the XML request to Nabil Bank PG and handle the response
    private function sendRequestToNabilBank($nabilBankUrl, $xmlRequest)
    {
        // Call the checkConnectionWithPortAndAPI method with the desired URL and port
$this->checkConnection();

$cert_file = storage_path('app/ssl/memtest.ican.org.np.crt.txt');

      
$key_file = storage_path('app/ssl/memtest.ican.org.np.key.txt');
        // $curl = curl_init();
    
        // curl_setopt_array($curl, [
        //     CURLOPT_URL => $nabilBankUrl,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_POST => true,
        //     CURLOPT_POSTFIELDS => $xmlRequest,
        //     CURLOPT_HTTPHEADER => [
        //         'Content-Type: application/xml',
        //         'SOAPAction: "executeTransaction"'
        //     ]
        // ]);
    
        // $response = curl_exec($curl);
    
        // if ($response === false) {
        //     $error = curl_error($curl);
        //     curl_close($curl);
        //     throw new Exception("Error occurred while making a request to Nabil Bank: $error");
        // }
    
        // curl_close($curl);
    
        // return $response;
        $url = "https://nabiltest.compassplus.com:8444/Exec";
        $xml ='<?xml version="1.0" encoding="UTF-8"?>
		<TKKPG>
		<Request><Operation>CreateOrder</Operation>
		<Language>EN</Language>
		<Order>
		<OrderType>Purchase</OrderType>
		<Merchant>NABIL106540</Merchant>
		<Amount>100</Amount>
		<Currency>524</Currency>
		<Description>123XXXX</Description>
		<ApproveURL></ApproveURL>
		<CancelURL></CancelURL>
		<DeclineURL></DeclineURL>
		<Fee>0</Fee>
		</Order>
		</Request>
		</TKKPG>';
        $headers = array(
            "Content-type: nabil_bank_request/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );
        
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  //for disabling curl certificate issue
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  // disabling curl certificate issue
curl_setopt($ch, CURLOPT_CAINFO, $cert_file);
curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
curl_setopt($ch, CURLOPT_SSLKEY, $key_file);


//curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $cert_password);


$data = curl_exec($ch);
echo $data;
// if(curl_errno($ch))
//     print curl_error($ch);
// else
//     curl_close($ch);

if(!$data)
{
	echo "Curl Error : " . curl_error($ch);
}
else
{
	echo htmlentities($data);
}
    }

    private function generateCreateOrderXML()
    {
        $xml = new SimpleXMLElement('<order/>');

        $xml->addChild('type', $this->orderType);

        if (!is_null($this->autoCaptureTime)) {
            $xml->addChild('autoCaptureTime', $this->autoCaptureTime);
        }

        if (!empty($this->additionalParams)) {
            $additionalParamsNode = $xml->addChild('additionalParams');

            foreach ($this->additionalParams as $key => $value) {
                $additionalParamsNode->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }
}