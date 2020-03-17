<?php
 if (isset($_POST['error'])) {
  echo "error:" .$_POST['error'];
  echo "<br>";
  echo "error_description:" .$_POST['error_description'];
  echo "<br>";
  exit;
 };

  $Push_Content['grant_type'] = "authorization_code";
  $Push_Content['code'] = $_POST['code'];
  $Push_Content['redirect_uri'] = "http://onlineplantweb.com.tw/online_plant_web/app/line/Callback.php";
  $Push_Content['client_id'] = "MWYOOsVnmtzhpQGlMrlZeJ";
  $Push_Content['client_secret'] = "2Xx2padzKGrAisKF0RqRpJlTPJCaQ4rQp9pgmjAS7q1";
 // Auth Line Official Connect Step-1
  $HTTP_Request_Header = getallheaders();
  $PassCallBackCheck = 0;
  if (isset($HTTP_Request_Header['Origin'])) {
   if ($HTTP_Request_Header['Origin'] == "https://notify-bot.line.me") {
    $PassCallBackCheck++;
   };
  };   if (isset($HTTP_Request_Header['Referer'])) {
   $ExplodeReferer = explode("&",parse_url($HTTP_Request_Header['Referer'],PHP_URL_QUERY));
   if (Count($ExplodeReferer) > 2) {
    if ($ExplodeReferer[1] == "client_id=".$Push_Content['client_id']) {
     $PassCallBackCheck++;
    };
   };
  };
  // if ($PassCallBackCheck != 2) {
  //  header('HTTP/1.1 403 Forbidden');
  //  exit;
  // };
 // Auth Line Official Connect Step-1
  
  echo 'Link ok!<br><hr>';
  print_r($Push_Content);
  echo "<hr>";
  echo json_encode($Push_Content);
  echo "<hr>";
    $ch = curl_init("https://notify-bot.line.me/oauth/token");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($Push_Content));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/x-www-form-urlencoded'
  ));
  $response_json_str = curl_exec($ch);
  curl_close($ch);
  echo $response_json_str.'<hr>';
   $response = json_decode($response_json_str, true);
  if (!isset($response['status']) || $response['status'] != 200 || !isset($response['access_token'])) {
      echo 'Request failed';
  } else if (preg_match('/[^a-zA-Z0-9]/u', $response['access_token'])) {
      echo 'Got wired access_token: '.$response['access_token']."<br>";
      echo 'http_response_header'.$http_response_header."<br>";
      echo 'response_json'.$response_json_str."<br>";
  } else {
      echo 'access_token: '.$response['access_token'];
  }
?>