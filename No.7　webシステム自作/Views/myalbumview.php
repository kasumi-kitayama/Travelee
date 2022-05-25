<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$album = $controller->myAlbumView();
$album_pages = $album['pages'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeアルバム詳細画面</title>
<link rel="stylesheet" type="text/css" href="/css/myalbumviewbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    $('button#delete').click(function(e) {
        e.preventDefault();
        var result = window.confirm("アルバムを削除します。よろしいですか？");
        if (result) {
          location.href = 'mypage.php?delete_album_id=<?=$album['id'] ?>';
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
        <div id="head_wrapper">
            <div id="organizer">
                <h1><?=$album['name'] ?></h1>
                <div id="edit_buttons">
                    <button id="add" class="button" onclick="location.href='addalbumpage.php?album_id=<?=$album['id'] ?>'">ページ追加</button>
                    <?php if(count($album_pages) > 0): ?>
                        <button id="edit" class="button" onclick="location.href='selecteditpage.php?album_id=<?=$album['id'] ?>'">編集</button>
                        <button id="delete" class="button">アルバム削除</button>
                    <?php endif; ?>
                </div>
            </div>
            <p id="error"><?php if(!empty($_SESSION['delete_album'])) echo $_SESSION['delete_album']; ?></p>
        </div>
        <div id="album_wrapper">
            <?php if(count($album_pages) == 0): ?>
                <p id="no_page">ページがありません。</p>
            <?php elseif(count($album_pages) == 1): ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[0]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[0]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide0" class="carousel__prev">Go to last slide</a>
                            <a href="#carousel__slide0" class="carousel__next">Go to first slide</a>
                        </li>
                    </ol>
                </section>
            <?php elseif(count($album_pages) == 2): ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[0]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[0]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide1" class="carousel__prev">Go to last slide</a>
                            <a href="#carousel__slide1" class="carousel__next">Go to next slide</a>
                        </li>
                        <li id="carousel__slide1" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[1]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[1]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[1]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[1]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide0" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide0" class="carousel__next">Go to first slide</a>
                        </li>
                    </ol>
                </section>
            <?php else: ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[0]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[0]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[0]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=count($album_pages)-1 ?>" class="carousel__prev">Go to last slide</a>
                            <a href="#carousel__slide1" class="carousel__next">Go to next slide</a>
                        </li>
                        <?php for($i=1; $i<count($album_pages)-1; $i++): ?>
                        <li id="carousel__slide<?=$i ?>" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[$i]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[$i]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[$i]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[$i]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=$i-1 ?>" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide<?=$i+1 ?>" class="carousel__next">Go to next slide</a>
                        </li>
                        <?php endfor; ?>
                        <li id="carousel__slide<?=count($album_pages)-1 ?>" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                            <?php if($album_pages[count($album_pages)-1]['image'] == 'no_image.jpg'): ?>
                                <div class="image_area">
                                    <img class="empty_images" src="/img/<?=$album_pages[count($album_pages)-1]['image'] ?>" alt="画像">
                                    <p class="no_image">No Image</p>
                                </div>
                            <?php else: ?>
                                <div class="image_area">
                                    <img class="images" src="/album_img/<?=$album_pages[count($album_pages)-1]['image'] ?>" alt="画像">
                                </div>
                            <?php endif; ?>
                                <p class="captions"><?=$album_pages[count($album_pages)-1]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=count($album_pages)-2 ?>" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide0" class="carousel__next">Go to first slide</a>
                        </li>
                    </ol>
                </section>
            <?php endif; ?>
            <?php if(count($album_pages) > 1): ?>
                <aside class="carousel__navigation">
                    <ol class="carousel__navigation-list">
                        <?php for($i=0; $i<count($album_pages); $i++): ?>
                        <li class="carousel__navigation-item">
                            <a href="#carousel__slide<?=$i ?>" class="carousel__navigation-button">Go to slide <?=$i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ol>
                </aside>
            <?php endif; ?>
        </div>
    </div>
</body>
<style>
</style>
</html>
