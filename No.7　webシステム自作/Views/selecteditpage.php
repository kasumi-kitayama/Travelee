<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$album = $controller->selectEditPage();
$pages = $album['pages'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee編集ページ選択画面</title>
<link rel="stylesheet" type="text/css" href="/css/selecteditpagebase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <section id="page_wrapper">
            <h1><?=$album['name'] ?></h1>
            <?php if(count($pages) == 0): ?>
                <p class="message">ページがありません。</p>
            <?php else: ?>
                <p class="message">編集したいページを選んでください。</p>
                <?php if(count($pages) == 1): ?>
                    <ol>
                        <li>
                            <a class="page" href="editmyalbum.php?album_id=<?=$album['id'] ?>&page_num=<?=$pages[0]['page_num'] ?>">
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
                            </a>
                        </li>
                    </ol>
                <?php else: ?>
                    <ol>
                    <?php for($i=0; $i<count($pages); $i++): ?>
                        <li>
                            <a class="page" href="editmyalbum.php?album_id=<?=$album['id'] ?>&page_num=<?=$pages[$i]['page_num'] ?>">
                            <?php if($pages[$i]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$pages[$i]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$pages[$i]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$pages[$i]['caption'] ?></p>
                            </a>
                        </li>
                    <?php endfor; ?>
                    </ol>
                <?php endif; ?>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
