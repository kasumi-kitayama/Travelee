<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: signin.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->myPage();
$user = $params['user'];
$albums = $params['albums'];
$images = $params['images'];

if($_SESSION['signin'] == 'on'):
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeマイページ画面</title>
<link rel="stylesheet" type="text/css" href="/css/mypagebase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="mypage.js"></script>
</head>
<body>
<?php
include(dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <section id="profile_wrapper">
            <div id="profile">
                <div id="profile_image_area">
                <?php if($user['image'] == 'smile.png'): ?>
                    <img class="profile_image" src="/img/<?=$user['image'] ?>" alt="プロフィール画像">
                <?php else: ?>
                    <img class="profile_image" src="/profile_img/<?=$user['image'] ?>" alt="プロフィール画像">
                <?php endif; ?>
                </div>
                <div id="bio">
                    <p id="profile_name"><?=$user['name'] ?></p>
                    <p id="profile_comment"><?php if(!empty($user['bio'])) echo $user['bio']; ?></p>
                    <button id="profile_button" onclick="location.href='editprofile.php'">プロフィール編集</button>
                </div>
            </div>
            <div id="button">
                <button class="buttons" onclick="location.href='mylist.php?user_id=<?=$user['id'] ?>'">マイリスト</button>
                <button class="buttons" onclick="location.href='myquestions.php'">Q-BOX</button>
                <button class="buttons" onclick="location.href='signin.php?signout'">ログアウト</button>
            </div>
        </section>
        <section id="albums_wrapper">
            <ol>
                <li>
                    <a class="albums" href="createmyalbum.php">
                        <div id="new_album">
                            <p id="plus">+</p>
                        </div>
                        <p class="album_names">新規作成</p>
                    </a>
                </li>

            <?php if(!empty($albums)): ?>
                <?php foreach($albums as $album): ?>
                <li>
                    <a class="albums" href="myalbumview.php?album_id=<?=$album['id'] ?>">
                        <div class="image_area">
                            <?php if(!empty($images[$album['id']]) && $images[$album['id']] !== 'no_image.jpg'): ?>
                                <img class="images" src="/album_img/<?=$images[$album['id']] ?>" alt="画像">
                            <?php else: ?>
                                <img class="empty_images" src="/img/no_image.jpg" alt="画像">
                                <p class="no_image">No Image</p>
                            <?php endif; ?>
                        </div>
                        <p class="album_names"><?=$album['name'] ?></p>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
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
 width: 100%;
 margin: 0 auto;
 padding: 150px 0 10px 0;
}

#profile_wrapper {
  display: flex;
  justify-content: space-between;
  width: 90%;
  max-width: 90%;
  height: 250px;
  margin: 60px auto;
}

#profile {
  display: flex;
  justify-content: space-around;
  width: 88%;
  max-width: 88%;
}

#profile_image_area {
 position: relative;
 height: 100%;
 width: 25%;
 max-width: 25%;
}

.profile_image {
  position: absolute;
  object-fit: contain;
  height: 100%;
  width: 100%;
}

#bio {
 width: 70%;
 max-width: 70%;
 height: 95%;
}

#profile_name {
 margin: 0;
 font-size: 40px;
 font-weight: bold;
 color: #444444;
}

#profile_comment {
  width: 80%;
  height: 40%;
  margin: 0 0 5px 0;
  padding: 15px 20px;
  font-size: 25px;
  font-weight: bold;
  color: #444444;
}

#profile_button {
  width: 350px;
  height: 60px;
  font-size: 30px;
  font-weight: bold;
  color: #006f86;
  background-color: #ffffff;
  border: 3px solid #006f86;
  border-radius: 10px;
}

#button {
  width: 10%;
  max-width: 10%;
  margin: auto;
}

.buttons {
 display: block;
 width: 150px;
 height: 50px;
 margin: 15px 0;
 font-size: 25px;
 font-weight: bold;
 color: #ffffff;
 background-color: #90d7ec;
 border: 3px solid #90d7ec;
 border-radius: 10px;
}

#albums_wrapper {
 display: flex;
 width: 86%;
 max-width: 86%;
 margin: 100px auto 0 auto;
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

#new_album {
  height: 65%;
  width: 90%;
  margin: 5% auto;
  border: 1px solid #444444;
  text-align: center;
  line-height: 50px;
}

#plus {
  width: 250px;
  margin: auto;
  line-height: 230px;
  font-size: 80px;
  color: #444444;
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

.image_area {
  height: 65%;
  width: 90%;
  margin: 5% auto;
}

.images {
  object-fit: cover;
  height: 100%;
  width: 100%;
}

.empty_images {
  object-fit: fill;
  height: 100%;
  width: 100%;
}

.no_image {
  margin: -150px 0 0 50px;
  font-size: 50px;
  color: #ffffff;
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
<?php
else: header('location: signin.php');
endif;
?>
