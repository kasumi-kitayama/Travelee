<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$token = $controller->confirmPassword();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeパスワードリセット画面</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/confirmpasswordbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    $('.eyebutton').on('click', function() {
        $(this).toggleClass('fa-eye-slash fa-eye');
        var input = $(this).prev('input');
        if(input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });
});
</script>
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <form action="completepassword.php" method="post">
            <h1 id="title">パスワードリセット</h1>
            <input type="hidden" name="token" value="<?=$token ?>">
                <label for="new_password">新しいパスワード</label>
                <div>
                    <input id="new_password" class="password" type="password" name="new_password">
                    <span class="eyebutton fa fa-eye-slash fa-2x"></span>
                    <p class="error"><?php if(!empty($_SESSION['different_passwords'])) echo $_SESSION['different_passwords']; ?></p>
                </div>
                <label for="password_to_check">確認用パスワード</label>
                <div>
                    <input id="password_to_check" class="password" type="password" name="password_to_check">
                    <span class="eyebutton fa fa-eye-slash fa-2x"></span>
                    <p class="error"><?php if(!empty($_SESSION['different_passwords'])) echo $_SESSION['different_passwords']; ?></p>
                </div>
            <p><?php if(!empty($_SESSION['reset_password'])) echo $_SESSION['reset_password']; ?></p>
            <input id="reset_button" type="submit" name="confirm_password" value="リセット">
        </form>
    </div>
</body>
</html>
