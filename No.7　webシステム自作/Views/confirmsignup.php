<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
    exit;
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$input = $controller->confirmSignUp();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeユーザ登録内容確認画面</title>
<link rel="stylesheet" type="text/css" href="/css/confirmsignupbase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <form id="form_wrapper" action="completesignup.php" method="post">
            <h1>ユーザ情報確認</h1>
            <p id="message">下記の内容で登録してよろしいですか？</p>
            <div class="item">
                <label>ユーザ名</label>
                <p class="display"><?=$input['name'] ?></p>
                <input type="hidden" name="name" value="<?=$input['name'] ?>">
            </div>
            <div class="item">
                <label>メールアドレス</label>
                <p class="display"><?=$input['email'] ?></p>
                <input type="hidden" name="email" value="<?=$input['email'] ?>">
            </div>
            <div class="item">
                <label>パスワード</label>
                <p class="display"><?=$input['hidden_password'] ?></p>
                <input type="hidden" name="password"  value="<?=$input['password'] ?>">
            </div>
            <input id="back_button" type="button" onclick="history.back()" value="↩戻る">
            <input id="ok_button" type="submit" name="signup" value="OK">
        </form>
    </div>
</body>
</html>
