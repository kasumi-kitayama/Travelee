<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->editMyAlbum();
$album = $params['album'];
$page = $params['page'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeアルバム編集画面</title>
<link rel="stylesheet" type="text/css" href="/css/editmyalbumbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
// ラジオボタンのON/OFF
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
// サムネイルを表示
$(function() {
    $('#image').change(function() {
        if(this.files.length > 0) {
            // 選択されたファイル情報を取得
            var file = this.files[0];
            // readerのresultプロパティにデータURLとしてエンコードされたファイルデータを格納
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function() {
                $('img').attr('src', reader.result);
                $('#no_img').hide();
            }
        }
    });
});
// アルバムページ削除
$(function() {
    $('#delete_button').click(function(e) {
        e.preventDefault();
        var del = window.confirm("ページを削除します。よろしいですか？");
        if (del) {
          location.href = 'myalbumview.php?album_id=<?=$album['id'] ?>&delete_page_num=<?=$page['page_num'] ?>';
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
            <input type="hidden" name="album_id" value="<?=$album['id'] ?>">
            <input type="hidden" name="page_num" value="<?=$page['page_num'] ?>">
            <input id="name" type="text" name="name" value="<?=$album['name'] ?>">
            <label id="private_label" for="private">公開</label><input id="private" type="radio" name="private" <?php if($album['private'] == 1): ?> checked <?php endif; ?>>
            <p class="error"><?php if(!empty($_SESSION['edit_album_name'])) echo $_SESSION['edit_album_name']; ?></p>
            <div class="thumbnail_area">
                <?php if($page['image'] == 'no_image.jpg'): ?>
                    <img id="empty_image" src="/img/<?=$page['image'] ?>">
                    <p id="no_img">No Image</p>
                <?php else: ?>
                    <img id="thumbnail" src="/album_img/<?=$page['image'] ?>">
                <?php endif; ?>
            </div>
            <input id="image" type="file" name="image" accept="image/*" value="<?=$page['image'] ?>">
            <input id="clear_image" type="radio" name="no_image"><label id="no_image_label" for="clear_image">写真を設定しない</label>
            <p class="error"><?php if(!empty($_SESSION['edit_album_image'])) echo $_SESSION['edit_album_image']; ?></p>
            <textarea type="text" name="caption"><?=$page['caption'] ?></textarea>
            <div>
                <input id="save_button" type="submit" name="edit_album" value="保存">
                <button id="delete_button" type="button">ページを削除</button>
            </div>
            <p class="error">
                <?php if(isset($_SESSION['edit_album'])) echo $_SESSION['edit_album']; ?>
                <?php if(isset($_SESSION['delete_album_page'])) echo $_SESSION['delete_album_page']; ?>
            </p>
        </form>
    </div>
</body>
</html>
