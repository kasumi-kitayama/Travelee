<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
    exit;
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->completeSignUp();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeユーザ登録完了画面</title>
<link rel="stylesheet" type="text/css" href="/css/completesignupbase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <p>ユーザ登録が完了しました。</p>
    </div>
</body>
</html>
