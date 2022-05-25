<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$mylist = $controller->editMyList();
$list = $mylist['list'];
$record = $mylist['record'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeマイリスト編集画面</title>
<link rel="stylesheet" type="text/css" href="/css/editmylistbase.css">
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <form action="mylistview.php?list_id=<?=$list['id'] ?>" method="post">
            <input type=hidden name="list_id" value="<?=$list['id'] ?>">
            <input type=hidden name="record_id" value="<?=$record['id'] ?>">
            <input class="input" type="text" name="name" placeholder="リスト名" value="<?=$list['name'] ?>">
            <p><?php if(!empty($_SESSION['edit_list_name'])) echo $_SESSION['edit_list_name']; ?></p>
            <input class="input" type="text" name="item" placeholder="項目" value="<?php if(isset($record['item'])) echo $record['item']; ?>">
            <textarea type="text" name="content" placeholder="コンテント"><?php if(isset($record['content'])) echo $record['content']; ?></textarea>
            <p><?php if(!empty($_SESSION['edit_list'])) echo $_SESSION['edit_list']; ?></p>
            <input id="save_button" type="submit" name="edit_list" value="保存">
        </form>
    </div>
</body>
</html>
