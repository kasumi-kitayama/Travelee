<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->othersAlbumView();
$user_id = $params['user_id'];
$album = $params['album'];
$pages = $params['pages'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee他ユーザ公開アルバム詳細画面</title>
<link rel="stylesheet" type="text/css" href="/css/othersalbumviewbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    var display = 'off';
    $('#question_button').on('click', function() {
        if(display == 'off') {
            $('#question_button').hide();
            $('form').show();
            display = 'on';
        }
    });

    $(document).on('click', function(e) {
        if(!$(e.target).closest('form').length && !$(e.target).closest('#question_button').length && !$(e.target).closest('#admin_button').length && !$(e.target).closest('a').length) {
            if(display == 'on') {
                $('form').hide();
                $('#question_button').show();
                display = 'off';
            }
        }
    });

    $('form').submit(function() {
        if ($('#content').val().length == 0) {
            alert("質問内容を入力してください。");
            return false;
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
        <h1><?=$album['name'] ?></h1>
        <div id="album_wrapper">
            <?php if(count($pages) == 0): ?>
                <p id="no_page">ページがありません。</p>
            <?php elseif(count($pages) == 1): ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[0]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[0]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[0]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[0]['caption'] ?></p>
                            </div>
                        </li>
                    </ol>
                </section>
            <?php elseif(count($pages) == 2): ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[0]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[0]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[0]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[0]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide1" class="carousel__prev">Go to last slide</a>
                            <a href="#carousel__slide1" class="carousel__next">Go to next slide</a>
                        </li>
                        <li id="carousel__slide1" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[1]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[1]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[1]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[1]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide0" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide0" class="carousel__next">Go to next slide</a>
                        </li>
                    </ol>
                </section>
            <?php else: ?>
                <section class="carousel" aria-label="Gallery">
                    <ol class="carousel__viewport">
                        <li id="carousel__slide0" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[0]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[0]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[0]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[0]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=count($pages)-1 ?>" class="carousel__prev">Go to last slide</a>
                            <a href="#carousel__slide1" class="carousel__next">Go to next slide</a>
                        </li>
                        <?php for($i=1; $i<=count($pages)-2; $i++): ?>
                        <li id="carousel__slide<?=$i ?>" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[$i]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[$i]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[$i]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[$i]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=$i-1 ?>" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide<?=$i+1 ?>" class="carousel__next">Go to next slide</a>
                        </li>
                        <?php endfor; ?>
                        <li id="carousel__slide<?=count($pages)-1 ?>" tabindex="0" class="carousel__slide">
                            <div class="carousel__snapper">
                                <div class="image_area">
                                    <?php if($pages[count($pages)-1]['image'] == 'no_image.jpg'): ?>
                                        <img class="empty_images" src="/img/<?=$pages[count($pages)-1]['image'] ?>" alt="画像">
                                        <p class="no_image">No Image</p>
                                    <?php else: ?>
                                        <img class="images" src="/album_img/<?=$pages[count($pages)-1]['image'] ?>" alt="画像">
                                    <?php endif; ?>
                                </div>
                                <p class="captions"><?=$pages[count($pages)-1]['caption'] ?></p>
                            </div>
                            <a href="#carousel__slide<?=count($pages)-2 ?>" class="carousel__prev">Go to previous slide</a>
                            <a href="#carousel__slide0" class="carousel__next">Go to next slide</a>
                        </li>
                    </ol>
                </section>
            <?php endif; ?>
            <?php if(count($pages) > 1): ?>
                <aside class="carousel__navigation">
                    <ol class="carousel__navigation-list">
                        <?php for($i=0; $i<count($pages); $i++): ?>
                            <li class="carousel__navigation-item">
                                <a href="#carousel__slide<?=$i ?>" class="carousel__navigation-button">Go to slide <?=$i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ol>
                </aside>
            <?php endif; ?>
        </div>
        <?php if(isset($_SESSION['signin']) && $_SESSION['role'] == 0 && count($pages) > 0): ?>
            <div id="admin_wrapper">
                <button id="admin_button" onclick="location.href='admin.php?admin=<?=$user_id ?>&album_id=<?=$pages[0]['album_id'] ?>'">管理</button>
            </div>
        <?php elseif(isset($_SESSION['signin']) && $_SESSION['role'] == 1 && $user_id !== $_SESSION['id'] && count($pages) > 0): ?>
            <div id="question_wrapper">
                <p id="message"><?php if(isset($_POST['question'])) echo "質問を送信しました。"; ?></p>
                <p><?php if(!empty($_SESSION['question'])) echo $_SESSION['question']; ?></p>
                <button id="question_button">質問する</button>
                <form action="othersalbumview.php?user_id=<?=$user_id ?>&album_id=<?=$pages[0]['album_id'] ?>" method="post">
                    <input id="user_id" type="hidden" name="user_id" value="<?=$_SESSION['id'] ?>">
                    <input id="album_id" type="hidden" name="album_id" value="<?=$pages[0]['album_id'] ?>">
                    <textarea id="content" type="text" name="content" placeholder="質問"></textarea>
                    <input id="submit_button" type="submit" name="question" value="送信">
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
<style>
body {
  margin: 0;
  padding: 0;
}

#wrapper {
  width: 100%;
  margin: 0 auto;
  padding: 130px 0 100px 0;
}

h1 {
  margin-left: 20%;
  font-size: 40px;
}

#album_wrapper {
  max-width: 40%;
  margin: 0 auto;
  padding: 0;
  font-family: 'Lato', sans-serif;
}

#no_page {
  font-size: 25px;
}

.image_area {
  width: auto;
  height: 65%;
  margin: 3% auto 0 auto;
  padding: 0;
  text-align: center;
}

.empty_images {
  position: relative;
  object-fit: fill;
  width: 80%;
  height: 100%;
}

.no_image {
  position: absolute;
  top: 40%;
  left: 50%;
  -ms-transform: translate(-50%,-50%);
  -webkit-transform: translate(-50%,-50%);
  transform: translate(-50%,-50%);
  margin: 0;
  padding: 0;
  font-size: 50px;
  color: #ffffff;
}

.images {
  object-fit: contain;
  width: auto;
  height: 100%;
}

.captions {
  height: 23%;
  width: 80%;
  margin: 3% 10%;
  font-size: 30px;
}

aside {
  position: fixed;
  top: 850px;
}

#admin_wrapper {
  width: 40%;
  max-width: 40%;
  margin: 50px auto 0 auto;
}

#admin_button {
  width: 120px;
  height: 50px;
  margin: 10px 0;
  font-size: 30px;
  color: #006f86;
  background-color: #ffffff;
  border: 3px solid #006f86;
  border-radius: 10px;
  cursor: pointer;
}

#question_wrapper {
  width: 40%;
  max-width: 40%;
  margin: 50px auto 0 auto;
}

#message {
  font-size: 25px;
}

#error {
  font-size: 25px;
  color: #ff0000;
}

#question_button {
  width: 150px;
  height: 50px;
  font-size: 30px;
  color: #006f86;
  background-color: #ffffff;
  border: 3px solid #006f86;
  border-radius: 10px;
  cursor: pointer;
}

form {
  display: none;
}

#content {
  width: 100%;
  height: 150px;
  margin: 10px 0 0 0;
  font-size: 30px;
  border-radius: 5px;
}

#submit_button {
  display: block;
  width: 120px;
  height: 50px;
  margin-top: 20px;
  font-size: 30px;
  color: #ffffff;
  background-color: #006f86;
  border: 3px solid #006f86;
  border-radius: 10px;
  cursor: pointer;
}

* {
  box-sizing: border-box;
  scrollbar-color: transparent transparent; /* thumb and track color */
  scrollbar-width: 0px;
}

*::-webkit-scrollbar {
  width: 0;
}

*::-webkit-scrollbar-track {
  background: transparent;
}

*::-webkit-scrollbar-thumb {
  background: transparent;
  border: none;
}

* {
  -ms-overflow-style: none;
}

ol, li {
  list-style: none;
  margin: 0;
  padding: 0;
}

.carousel {
  position: relative;
  padding-top: 75%;
  filter: drop-shadow(0 0 10px #0003);
  perspective: 100px;
}

.carousel__viewport {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  overflow-x: scroll;
  counter-reset: item;
  scroll-behavior: smooth;
  scroll-snap-type: x mandatory;
}

.carousel__slide {
  position: relative;
  flex: 0 0 100%;
  width: 100%;
  counter-increment: item;
  border: 1px solid;
}

.carousel__slide:before {
  content: counter(item);
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate3d(-50%,-40%,70px);
  color: #fff;
  font-size: 2em;
}

.carousel__snapper {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  scroll-snap-align: center;
}


@media (hover: hover) {
  .carousel__snapper {
    animation-name: tonext, snap;
    animation-timing-function: ease;
    animation-duration: 4s;
    animation-iteration-count: infinite;
  }

  .carousel__slide:last-child .carousel__snapper {
    animation-name: tostart, snap;
  }
}

@media (prefers-reduced-motion: reduce) {
  .carousel__snapper {
    animation-name: none;
  }
}

.carousel:hover .carousel__snapper,
.carousel:focus-within .carousel__snapper {
  animation-name: none;
}

/* 画像サイズ */
.carousel__navigation {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  text-align: center;
}

/* ナビゲーション */
.carousel__navigation-list,
.carousel__navigation-item {
  display: inline-block;
}

.carousel__navigation-button {
  display: inline-block;
  width: 1.5rem;
  height: 1.5rem;
  background-color: #333;
  background-clip: content-box;
  border: 0.25rem solid transparent;
  border-radius: 50%;
  font-size: 0;
  transition: transform 0.1s;
}

/* スライドボタン */
.carousel::before,
.carousel::after,
.carousel__prev,
.carousel__next {
  position: absolute;
  top: 0;
  margin-top: 37.5%;
  width: 4rem;
  height: 4rem;
  transform: translateY(-50%);
  border-radius: 50%;
  font-size: 0;
  outline: 0;
}

.carousel::before,
.carousel__prev {
  left: -1rem;
}

.carousel::after,
.carousel__next {
  right: -1rem;
}

.carousel::before,
.carousel::after {
  content: '';
  z-index: 1;
  background-color: #333;
  background-size: 1.5rem 1.5rem;
  background-repeat: no-repeat;
  background-position: center center;
  color: #fff;
  font-size: 2.5rem;
  line-height: 4rem;
  text-align: center;
  pointer-events: none;
}

.carousel::before {
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolygon points='0,50 80,100 80,0' fill='%23fff'/%3E%3C/svg%3E");
}

.carousel::after {
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolygon points='100,50 20,100 20,0' fill='%23fff'/%3E%3C/svg%3E");
}
</style>
</html>
