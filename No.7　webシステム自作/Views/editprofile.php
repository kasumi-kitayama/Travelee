<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}

require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$user = $controller->profile();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeプロフィール編集画面</title>
<link rel="stylesheet" type="text/css" href="/css/editprofilebase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function () {
    // サムネイル表示
    $('#image').change(function() {
        if(this.files.length > 0) {
            // 選択されたファイル情報を取得
            var file = this.files[0];
            // readerのresultプロパティにデータURLとしてエンコードされたファイルデータを格納
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function() {
                $('.thumbnail').attr('src', reader.result);
            }
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
        <form action="mypage.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$user['id'] ?>">
            <div class="item">
                <label for="name">ユーザ名<label>
                <div class="organizer">
                    <input id="name" type="text" name="name" placeholder="ユーザ名" value="<?=$user['name'] ?>">
                    <p><?php if(!empty($_SESSION['profile_name'])) echo $_SESSION['profile_name']; ?></p>
                </div>
            </div>
            <div class="item">
                <label for="image">プロフィール写真<label>
                    <div id="thumbnail_area" class="organizer">
                    <?php if($user['image'] == 'smile.png'): ?>
                        <img class="thumbnail" src="/img/<?=$user['image'] ?>">
                    <? else: ?>
                        <img class="thumbnail" src="/profile_img/<?=$user['image'] ?>">
                    <? endif; ?>
                    </div>
                <input id="image" type="file" name="image" accept="image/*" value="<?=$user['image'] ?>">
                <p><?php if(!empty($_SESSION['profile_image'])) echo $_SESSION['profile_image']; ?></p>
            </div>
            <div>
                <label for="bio">自己紹介<label>
                <div class="organizer">
                    <textarea id="bio" type="text" name="bio" placeholder="自己紹介（200文字以内）"><?php if(!empty($user['bio'])) echo $user['bio']; ?></textarea>
                    <p><?php if(!empty($_SESSION['profile_bio'])) echo $_SESSION['profile_bio']; ?></p>
                </div>
            </div>
            <input id="save_button" type="submit" name="profile" value="保存">
        </form>
    </div>
</body>
</html>
