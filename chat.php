 <?php

 $input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];

$pagetoken = "***********"; // Facebook TOKEN
// change... USER ,TOKEN, FROM to ur own
// Proovl API https://www.website.com/chat.php
// or ..
$user = "Kyle Mullikin"; //Proovl UserID
$token = "***************"; //Proovl Token
$from = "************"; //Proovl Number
// or you add your own site : w/out domain i cant put up web iterator_apply
$obj = json_decode($input);
$result = explode(';',$message);



if ($message == "instruction") {

 $message_to_reply = "Message format: Number;Text";

}

else {
// exmaple User ID: 545456188323111233
$white = array("**************");

if (in_array("$sender", $white)) {

// send sms
if (is_numeric($result[0]))
{


	$url = "https://www.proovl.com/api/send.php";

	$postfields = array(
		'user' => "$user",
		'token' => "$token",
		'from' => "$from",
		'to' => "$result[0]",
		'text' => "$result[1]"
	);

	if (!$curld = curl_init()) {
		exit;
	}

	curl_setopt($curld, CURLOPT_POST, true);
	curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($curld, CURLOPT_URL,$url);
	curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

	$output = curl_exec($curld);

	curl_close ($curld);

	$status = explode(';',$output);

	 $message_to_reply = "[$result[0]|$result[1]]     Message ID: $status[1]; Status: $status[0]";

}

   else { $message_to_reply = "Wrong Format!🦊 Type:instruction"; }

}

else {  $message_to_reply = "Access denied 😕 Contact Admin 📞"; }

}

if(preg_match('[time|current time|now]', strtolower($message))) {
// avoid a lot of requests

 if($input != '') {

 $message_to_reply = $result;

 }

} else {



$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$pagetoken";
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.

$jsonData = '{
 "recipient":{
 "id":"'.$sender.'"
 },
  "message":{
     "text":"'.$message_to_reply.'"
  }
}';

//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array(‘Content-Type: application/x-www-form-urlencoded’));
//Execute the request

if(!empty($input['entry'][0]['messaging'][0]['message'])){
 $result = curl_exec($ch);
}

}


/* Verification Facebook webhook
$access_token = "****************"; // Facebook token
$verify_token = "my-token";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
 $challenge = $_REQUEST['hub_challenge'];
 $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
 echo $challenge;
}
*/

?>
