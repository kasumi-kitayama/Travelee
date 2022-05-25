<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->resign();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee退会確認画面</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/css/resignbase.css">
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
        <?php if(!empty($_SESSION['id'])): ?>
            <div id="main_wrapper">
                <p id="message">データが消えてしまいますが、退会してよろしいですか？</p>
                <button class="button" onclick="location.href='signin.php'">戻る</button>
                <button class="button" onclick="location.href='completeresign.php?resign=<?=$_SESSION['id'] ?>'">退会する</button>
            </div>
        <?php else: ?>
            <form action="resign.php" method="post">
                <label for="email">メールアドレス</label>
                <div>
                    <input id="email" type="email" name="email" placeholder="test@co.jp">
                    <p class="error" ><?php if(!empty($_SESSION['resign_email'])) echo $_SESSION['resign_email']; ?></p>
                </div>
                <label for="password">パスワード</label>
                <div>
                    <input id="password" type="password" name="password">
                    <span class="eyebutton fa fa-eye-slash fa-2x"></span>
                    <p class="error" ><?php if(!empty($_SESSION['resign_password'])) echo $_SESSION['resign_password']; ?></p>
                </div>
                <input id="resign_button" type="submit" name="resign" value="退会する">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
