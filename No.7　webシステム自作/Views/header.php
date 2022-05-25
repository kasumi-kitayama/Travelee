<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeヘッダー</title>
<style>
body {
  margin: auto;
}

#header_wrapper {
  position: fixed;
  display: flex;
  justify-content: space-between;
  width: 100%;
  max-width: 100%;
  background-color: #ff9900;
  z-index: 500;
}

#header_title {
  margin: 0 0 -53px -2px;
  font-size: 120px;
  color: #ffffff;
}

#header_links {
  margin: auto 10px 0 0;
}

.header_link {
  margin-left: 25px;
  font-size: 30px;
  color: #ffffff;
}
</style>
</head>
<body>
    <div id="header_wrapper">
        <h1 id="header_title">Travelee</h1>
        <div id="header_links">
            <a class="header_link" href="signin.php">ログイン</a>
            <a class="header_link" href="mypage.php">マイページ</a>
            <a class="header_link" href="index.php">トップへ</a>
        </div>
  </div>
</body>
</html>
