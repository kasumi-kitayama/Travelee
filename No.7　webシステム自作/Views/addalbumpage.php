<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$album = $controller->addAlbumPage();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeアルバムページ追加画面</title>
<link rel="stylesheet" type="text/css" href="/css/addalbumpagebase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
// サムネイル表示
$(function () {
    $('#image').change(function() {
        if(this.files.length > 0) {
            // 選択されたファイル情報を取得
            var file = this.files[0];
            // readerのresultプロパティにデータURLとしてエンコードされたファイルデータを格納
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function() {
                $('#thumbnail').attr('src', reader.result);
                $('#thumbnail').css('object-fit', 'contain');
                $('#no_image').remove();
            }
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
        <form action="myalbumview.php" method="post" enctype="multipart/form-data">
            <h1><?=$album['name'] ?></h1>
            <input type="hidden" name="album_id" value="<?=$album['id'] ?>">
            <div id="thumbnail_area">
                <img id="thumbnail" src="/img/no_image.jpg">
                <p id="no_image">No Image</p>
            </div>
            <input id="image" type="file" name="image" accept="image/*">
            <p class="error"><?php if(!empty($_SESSION['album_image'])) echo $_SESSION['album_image']; ?></p>
            <textarea id="caption" type="text" name="caption"></textarea>
            <input id="button" type="submit" name="add_page" value="ページを追加">
            <p class="error"><?php if(!empty($_SESSION['add_page'])) echo $_SESSION['add_page']; ?></p>
        </form>
    </div>
</body>
</html>
