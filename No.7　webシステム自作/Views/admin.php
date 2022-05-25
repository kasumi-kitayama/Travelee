<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->admin();
$user_id = $params['user_id'];
$album = $params['album'];
$pages = $params['pages'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee管理画面</title>
<link rel="stylesheet" type="text/css" href="/css/adminbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(function() {
    $('#delete_album_button').on('click', function(e) {
        var album = window.confirm("アルバムを削除します。よろしいですか？");
        if (album) {
            location.href = 'index.php?delete=album&album_id=<?=$album['id'] ?>';
        }
    });

    $('#delete_user_button').on('click', function(e) {
        var user = window.confirm("ユーザを削除します。よろしいですか？");
        if (user) {
            location.href = 'index.php?delete=user&user_id=<?=$user_id ?>';
        }
    });

    $('.page').on('click', function() {
        var page_num = $(this).attr('id');
        var page = window.confirm("ページを削除します。よろしいですか？");
        if (page) {
            location.href = 'admin.php?user_id=<?=$user_id ?>&album_id=<?=$album['id'] ?>&delete=' + page_num;
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
        <div id="title_wrapper">
            <h1 id="title"><?=$album['name'] ?></h1>
        </div>
        <section>
        <?php if(count($pages) == 0): ?>
            <p>ページがありません。</p>
        <?php else: ?>
            <div id="directions">
                <p id="message">削除するページを選んでください。</p>
                <div>
                    <button id="delete_album_button" class="delete_button">アルバムの削除</button>
                    <button id="delete_user_button" class="delete_button">ユーザの削除</button>
                </div>
            </div>
            <p class="error"><?php if(!empty($_SESSION['delete'])) echo $_SESSION['delete']; ?></p>
            <?php if(count($pages) == 1): ?>
                <li>
                    <div id="<?=$pages[0]['page_num'] ?>" class="page">
                    <?php if($pages[0]['image'] == 'no_image.jpg'): ?>
                        <div class="image_area">
                            <img class="empty_images" src="/img/<?=$pages[0]['image'] ?>" alt="画像">
                            <p class="no_image">No Image</p>
                        </div>
                    <?php else: ?>
                        <div class="image_area">
                            <img class="images" src="/album_img/<?=$pages[0]['image'] ?>" alt="画像">
                        </div>
                    <?php endif; ?>
                        <p class="captions"><?=$pages[0]['caption'] ?></p>
                    </div>
                </li>
            <?php else: ?>
                <ol>
                <?php for($i=0; $i<count($pages); $i++): ?>
                    <li>
                        <div id="<?=$pages[$i]['page_num'] ?>" class="page">
                        <?php if($pages[$i]['image'] == 'no_image.jpg'): ?>
                            <div class="image_area">
                                <img class="empty_images" src="/album_img/<?=$pages[$i]['image'] ?>" alt="画像">
                                <p class="no_image">No Image</p>
                            </div>
                        <?php else: ?>
                            <div class="image_area">
                                <img class="images" src="/album_img/<?=$pages[$i]['image'] ?>" alt="画像">
                            </div>
                        <?php endif; ?>
                            <p class="captions"><?=$pages[$i]['caption'] ?></p>
                        </div>
                    </li>
                <?php endfor; ?>
                </ol>
            <?php endif; ?>
        <?php endif; ?>
        </section>
    </div>
</body>
</html>
