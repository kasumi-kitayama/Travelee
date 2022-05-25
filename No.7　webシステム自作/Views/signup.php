<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeユーザ登録入力画面</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/signupbase.css">
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
        <form id="form_wrapper" action="confirmsignup.php" method="post">
            <h1>サインアップ</h1>
            <div>
                <label for="name">ユーザ名</label>
                <input id="name" class="input" type="text" name="name" placeholder="山田太郎" value="<?php if(isset($_SESSION['signup_name'])) echo $_SESSION['signup_name']; ?>">
                <p><?php if(!empty($_SESSION['signup_error_name'])) echo $_SESSION['signup_error_name']; ?></p>
            </div>
            <div>
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" placeholder="email@co.jp" value="<?php if(isset($_SESSION['signup_email'])) echo $_SESSION['signup_email']; ?>">
                <p><?php if(!empty($_SESSION['signup_error_email'])) echo $_SESSION['signup_error_email']; ?></p>
            </div>
            <div>
                <label for="password">パスワード</label>
                <input id="password" class="input" type="password" name="password">
                <span class="eyebutton fa fa-eye-slash  fa-2x"></span>
                <p><?php if(!empty($_SESSION['signup_error_password'])) echo $_SESSION['signup_error_password']; ?></p>
            </div>
            <div>
                <label for="password_to_check">確認用パスワード</label>
                <input id="password_to_check" class="input" type="password" name="password_to_check">
                <span class="eyebutton fa fa-eye-slash  fa-2x"></span>
                <p><?php if(!empty($_SESSION['signup_error_password'])) echo $_SESSION['signup_error_password']; ?></p>
            </div>
            <input id="check_button" type="submit" name="to_signup" value="確認">
        </form>
    </div>
</body>
</html>
