<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->completeResign();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee退会完了画面</title>
<link rel="stylesheet" type="text/css" href="/css/completeresignbase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <p id="message">退会しました。</p>
    </div>
</body>
<style>
body {
  margin: 0 auto;
  padding: 0;
}

#wrapper {
  width: 90%;
  margin: 0 auto;
  padding: 200px 0 100px 0;
}

#message {
  font-size: 25px;
}
</style>
</html>
