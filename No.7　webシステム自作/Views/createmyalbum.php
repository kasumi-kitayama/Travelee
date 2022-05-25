<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->createMyAlbum();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeアルバム作成画面</title>
<link rel="stylesheet" type="text/css" href="/css/createmyalbumbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    var input = $('input'); // input要素を取得する
    var checked = input.filter(':checked').val(); // 読み込み時に「:checked」の疑似クラスを持っているinputの値を取得する
    input.on('click', function() {
        if($(this).val() === checked) {
            $(this).prop('checked', false);
            checked = ''; // checkedを初期化
        } else {
            $(this).prop('checked', true);
            checked = $(this).val(); // inputの値をcheckedに代入
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
        <form action="addalbumpage.php" method="post" enctype="multipart/form-data">
            <div>
                <input type="hidden" name="user_id" value="<?=$_SESSION['id'] ?>">
                <input id="name" type="text" name="name" placeholder="アルバム名">
                <label for="private">公開</label><input id="private" type="radio" name="private">
            </div>
            <input id="button" type="submit" name="create_album" value="作成">
        </form>
    </div>
</body>
</html>
