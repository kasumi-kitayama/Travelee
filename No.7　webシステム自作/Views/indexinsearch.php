<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->indexinsearch();
$albums = $params['albums'];
$search = $params['search'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee検索画面</title>
<link rel="stylesheet" type="text/css" href="/css/base.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    $('#magnifying_glass').on('click', function() {
        $('#search_bar').toggleClass('hide');
    });
});
</script>
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper"><p>
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
            <input type="hidden" id="search" value="<?php print_r($search); ?>">
            <ol id="content">
            <?php if(empty($albums)): ?>
                <p>条件に一致するアルバムはありません。</p>
            <?php elseif(count($albums) == 1): ?>
                <li>
                    <a class="albums" href="othersalbumview.php?album_id=<?=$albums[0]['id'] ?>&user_id=<?=$albums[0]['user_id'] ?>">
                        <?php if($albums[0]['image'] == 'no_image.jpg'): ?>
                          <div class="empty_image">
                              <img class="image" src="/img/<?=$albums[0]['image'] ?>" alt="画像">
                              <p class="no_image">No Image</p>
                          </div>
                        <?php else: ?>
                            <div class="image_area">
                                <img class="image" src="/album_img/<?=$albums[0]['image'] ?>" alt="画像">
                            </div>
                        <?php endif; ?>
                        <p class="album_names"><?=$albums[0]['name'] ?></p>
                    </a>
                </li>
            <?php else: ?>
                <?php foreach($albums as $album): ?>
                <li>
                    <a class="albums" href="othersalbumview.php?album_id=<?=$album['id'] ?>&user_id=<?=$album['user_id'] ?>">
                        <?php if($album['image'] == 'no_image.jpg'): ?>
                          <div class="empty_image">
                              <img class="image" src="/img/<?=$album['image'] ?>" alt="画像">
                              <p class="no_image">No Image</p>
                          </div>
                        <?php else: ?>
                            <div class="image_area">
                                <img class="image" src="/album_img/<?=$album['image'] ?>" alt="画像">
                            </div>
                        <?php endif; ?>
                        <p class="album_names"><?=$album['name'] ?></p>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
            </ol>
        </section>
    </div>
</body>
<style>
body {
 position: relative;
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
  width: 40%;
  height: 80px;
  margin-left: auto;
  border: 1px solid #257985;
  border-radius: 15px;
  background-color: #257985;
  z-index: 500;
}

.hide {
  margin-right: -35.5%;
  opacity: 0.7;
}

#magnifying_glass {
  width: 10%;
  height: 70px;
  margin: 5px;
  border-radius: 10px;
}

#search_box {
  display: flex;
  width: 87%;
  margin: auto 0;
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

#album_wrapper {
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
  margin: 0 2% 0 4%;
}

li:nth-of-type(4n-1) {
  margin: 0 4% 0 2%;
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
</style>
</html>
