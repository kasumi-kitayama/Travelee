<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->resetPassword();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeパスワードリセット画面</title>
<link rel="stylesheet" type="text/css" href="/css/resetpasswordbase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <form action="sentemail.php" method="post">
            <p class="error"><?php if(isset($_SESSION['verify_token'])) echo $_SESSION['verify_token']; ?></p>
            <p id="message">登録されているアドレスにパスワードリセット画面のURLが記載されたメールを送ります。</p>
            <input id="email" type="email" name="email" placeholder="test@co.jp">
            <p class="error"><?php if(isset($_SESSION['reset_password_email'])) echo $_SESSION['reset_password_email']; ?></p>
            <input id="submit_button" type="submit" name="send" value="送信">
        </form>
    </div>
</body>
</html>
