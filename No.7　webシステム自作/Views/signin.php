<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->signIn();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeログイン画面</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/signinbase.css">
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
include(dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <form id="form_wrapper" action="mypage.php" method="post">
            <h1>ログイン</h1>
            <div class="items">
                <label for="email">メールアドレス</label>
                <div>
                    <input id="email" type="email" name="email" placeholder="test@co.jp" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>">
                    <p><?php if(isset($_SESSION['validate_signin_email'])) echo $_SESSION['validate_signin_email']; ?></p>
                </div>
                <label for="password">パスワード</label>
                <div>
                    <input id="password" type="password" name="password">
                    <i class="eyebutton fa fa-eye-slash fa-2x"></i>
                    <p><?php if(isset($_SESSION['verify_signin_password'])) echo $_SESSION['verify_signin_password']; ?></p>
                </div>
            </div>
            <input id="signin_button" type="submit" name="signin" value="ログイン">
        </form>

        <div id="link_wrapper">
            <a class="link" href="resetpassword.php">パスワードを忘れた方はこちら</a>
            <a class="link" href="signup.php">ユーザ登録をする</a>
            <a class="link" href="resign.php">退会する</a>
        </div>
    </div>
</body>
</html>
