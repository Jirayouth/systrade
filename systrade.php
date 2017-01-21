<?php
$access_token = 'qtfW2whmIBLG4ui5DO1+wIB2vxRW8dUX7t6ksc4ZtYnL6Y/4ZDShhwB0fk03JUkdyStOJ183vPL4oRD9ZEfuHT0eQ4jVw6oBt8+QxNnh4YzFxgSSWoFU+t9JYRBOXk2cwHW0YtzJsQVKVxpOGVhCCgdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Get user
			$user = $event['source']['text'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
			
			$myfile = fopen("nm_list.txt", "w") or die("Unable to open file!");
			$txt = $user+" : "+$replyToken+"\n";
			fwrite($myfile, $txt);
			$txt = "Minnie Mouse\n";
			fwrite($myfile, $txt);
			fclose($myfile);
		}
	}
}
echo "OK";
