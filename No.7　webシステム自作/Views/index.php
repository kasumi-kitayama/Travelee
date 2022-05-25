<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->index();
$albums = $params['bodies'];
$images = $params['images'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeトップ画面</title>
<link rel="stylesheet" type="text/css" href="/css/base.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="index.js"></script>
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <div id="search_bar" class="hide">
            <img id="magnifying_glass" src="/img/search.png" alt="検索">
            <form id="search_box" action="indexinsearch.php" method="post">
                <select name="object">
                    <option hidden>選択してください</option>
                    <option value="album_name">アルバム名</option>
                    <option value="caption">キャプション</option>
                </select>
                <input id="key_word" type="text" name="key_word">
                <input id="search_button" type="submit" name="search" value="検索">
            </form>
        </div>
        <section id="album_wrapper">
            <ol id="content">
                <?php foreach($albums as $album): ?>
                <li>
                    <a class="albums" href="<?php if($album['user_id'] == $_SESSION['id']): ?>myalbumview.php?album_id=<?php echo $album['id']; else: ?>othersalbumview.php?album_id=<?=$album['id'] ?>&user_id=<?php echo $album['user_id']; endif;?>">
                        <?php if(!empty($images[$album['id']]) && $images[$album['id']] !== 'no_image.jpg'): ?>
                            <div class="image_area">
                                <img class="image" src="/album_img/<?=$images[$album['id']] ?>" alt="画像">
                            </div>
                        <?php elseif(!empty($images[$album['id']]) && $images[$album['id']] == 'no_image.jpg'): ?>
                            <div class="empty_image">
                                <img class="image" src="/img/<?=$images[$album['id']] ?>" alt="画像">
                                <p class="no_image">No Image</p>
                            </div>
                        <?php elseif(empty($images[$album['id']])): ?>
                            <div class="empty_image">
                                <img class="image" src="/img/no_image.jpg" alt="画像">
                                <p class="no_image">No Image</p>
                            </div>
                        <?php endif; ?>
                        <p class="album_names"><?=$album['name'] ?></p>
                    </a>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>
        <a id="top" href="">
            <button id="top_button">TOPへ</button>
        </a>
    </div>
</body>
<style>
body {
 margin: 0 auto;
}

#wrapper {
  overflow: hidden;
  width: 100%;
  margin: 0 auto;
  padding: 180px 0 10px 0;
}

#search_bar {
  position: fixed;
  top: 150px;
  right: 0px;
  display: flex;
  width: 700px;
  height: 80px;
  margin-left: auto;
  border: 1px solid #257985;
  border-radius: 15px;
  background-color: #257985;
  z-index: 500;
}

.hide {
  margin-right: -620px;
  opacity: 0.7;
}

#magnifying_glass {
  width: 70px;
  height: 70px;
  margin: 5px;
  border-radius: 10px;
}

#search_box {
  display: flex;
  width: 610px;
  /* height: 60px; */
  margin: auto 0;
  /* margin-top: 7px; */
  /* margin-left: -3px; */
  /* vertical-align: top; */
  border-color: #ffffff;
}

select {
  width: 25%;
  font-size: 15px;
}

#key_word {
  width: 65%;
  height: 60px;
  font-size: 30px;
}

#search_button {
  width: 10%;
  margin-left: 5px;
  text-align: center;
  font-size: 23px;
  color: #ffffff;
  background-color: #aadddd;
  border: 1px solid #ffffff;
  border-radius: 5px;
}

#album_wrapper {/* padding: 20px 30px; #wrapperの代わり?*/
 display: flex;
 width: 86%;
 max-width: 86%;
 margin: 0 auto;
 padding: 0;
}

ol, li {
 padding: 0;
 list-style: none;
}

ol {
 display: flex;
 flex-wrap: wrap;
}

li {
 margin-bottom: 30px;
}

li:nth-of-type(4n-2) {
  margin: 0 35px 0 70px;
}

li:nth-of-type(4n-1) {
  margin: 0 70px 0 35px;
}

.albums {
  display: block;
  height: 330px;
  width: 380px;
  border: 1px solid #444444;
  text-decoration: none;
  border-radius: 25px;
}

.image_area {
 height: 65%;
 width: 90%;
 margin: 5% auto;
}

.image {
  object-fit: cover;
  height: 100%;
  width: 100%;
}

.empty_image {
  position: relative;
  height: 65%;
  width: 90%;
  margin: 5% auto;
}

.no_image {
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%,-50%);
  -webkit-transform: translate(-50%,-50%);
  transform: translate(-50%,-50%);
  margin: 0;
  padding: 0;
  font-size: 40px;
  color: #ffffff;
}

.album_names {
  height: 20%;
  width: 90%;
  margin: 6% auto;
  font-size: 35px;
  font-weight: bold;
  color: #444444;
  text-align: center;
}

#top {
  position: fixed;
  right: 20px;
  bottom: 10px;
}

#top_button {
  width: 120px;
  height: 100px;
  font-size: 25px;
  font-weight: 500;
  color: #ffffff;
  background-color: #006f86;
  border: 1px solid #ffffff;
  border-radius: 5px;
  cursor: pointer;
}
</style>
</html>
