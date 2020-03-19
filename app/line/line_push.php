<?php
$get_text = $_GET['text'];
$access_token = array();
// $access_token[]="lPuzbsvo2oZo28hVuBHtA2ZXAkqP9XyKAmT0aRo1YoQ";
$access_token[]="ihxw66gKunhjooYXGjj0AjD8RKZ0r2trdoQG6MMQCCT";
$message=$get_text."\n現在時刻: ".date('Y-m-d H:i:s');
$TargetCount = count($access_token);
   $Push_Content['message'] = $message;
  // $Push_Content['imageThumbnail'] = "https://i.imgur.com/ZxuJGHG.png";
  // $Push_Content['imageFullsize'] = "https://i.imgur.com/ZxuJGHG.png";
  // $Push_Content['stickerPackageId'] = "3";
  // $Push_Content['stickerId'] = "180";
  for ($i=0;$i<$TargetCount;$i++) {
   $ch = curl_init("https://notify-api.line.me/api/notify");
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($Push_Content));
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.$access_token[$i]
   ));
   $response_json_str = curl_exec($ch);
   curl_close($ch);
   echo $response_json_str."<br>\r\n";
   // {"status":400,"message":"LINE Notify account doesn't join group which you want to send."}
   // {"status":401,"message":"Invalid access token"}
   // {"status":400,"message":"message: must not be empty"}
   $response = json_decode($response_json_str, true);
   print_r($response);
   echo "<hr>";
   if ( (!isset($response['status'])) || (!isset($response['message'])) ) {
    echo "Request failed";
    exit;
   };
   if ( ($response['status'] != 200) || ($response['message'] != 'ok') ) {
    echo "Request failed";
    exit;
   };
   if (!isset($response['access_token'])) {
    $ch = curl_init("https://notify-api.line.me/api/status");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
     'Authorization: Bearer '.$access_token[$i]
    ));
    $response_json_str = curl_exec($ch);
    curl_close($ch);
        echo $response_json_str."<hr>";
   } else if (preg_match('/[^a-zA-Z0-9]/u', $response['access_token'])) {
    echo 'Got wired access_token: '.$response['access_token']."<br>";
    echo 'http_response_header'.$http_response_header."<br>";
    echo 'response_json'.$response_json_str."<br>";
   } else {
    echo 'access_token: '.$response['access_token'];
   }
   usleep(6000); // microseconds * 1000 = miliseconds
  };
 ?>
