<!DOCTYPE html>
<html lang="tw">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
        function oAuth2() {
            var URL = 'https://notify-bot.line.me/oauth/authorize?';
            URL += 'response_type=code';
            URL += '&client_id=MWYOOsVnmtzhpQGlMrlZeJ';
            URL += '&redirect_uri=https://localhost/online_web/app/line/Callback.php';
            URL += '&scope=notify';
            URL += '&state=NO_STATE';
            URL += '&response_mode=form_post';
            window.location.href = URL;
        }
    </script>
    </head>
    <body>
        <button onclick="oAuth2();"> 連結到 LineNotify 按鈕 </button>
    </body>
</html>