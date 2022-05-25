<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$mylist = $controller->myListView();
$list = $mylist['list'];
$records = $mylist['records'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeマイリスト詳細画面</title>
<link rel="stylesheet" type="text/css" href="/css/mylistviewbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
// マイリストレコード削除
$(function() {
    $('#add_list').click(function() {
        $('#form_wrapper').show();
    });

    $('button#delete').click(function(e) {
          e.preventDefault();
          var del = window.confirm("リストを削除します。よろしいですか？");
          if (del) {
              location.href = $(this).val();
          }
      });
});
</script>
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id=wrapper>
        <div id="title_wrapper">
            <h1 id="title"><?=$list['name'] ?></h1>
            <div>
                <button id="add_list" class="list_button" onclick="location.href='mylistview.php?list_id=<?=$list['id'] ?>#form'">リストを追加する</button>
                <button class="list_button" onclick="location.href='mylist.php?user_id=<?=$list['user_id'] ?>'">マイリストに戻る</button>
            </div>
        </div>
        <div id="table_wrapper">
            <?php if(empty($records)): ?>
                <p id="message">リストがありません。</p>
            <?php else: ?>
                <table>
                <?php foreach($records as $record): ?>
                    <tr>
                        <th><?=$record['item'] ?></th>
                        <td class="content"><?=$record['content'] ?></td>
                        <td id="edit_buttons">
                            <button class="edit_button" onclick="location.href='editmylist.php?list_id=<?=$list['id'] ?>&record_id=<?=$record['id'] ?>'">編集</button>
                            <button id="delete" class="edit_button" value="mylistview.php?list_id=<?=$list['id'] ?>&delete=<?=$record['id'] ?>">削除</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
                <p class="error"><?php if(!empty($_SESSION['delete_record'])) echo $_SESSION['delete_record']; ?></p>
            <?php endif; ?>
        </div>
        <form id="form_wrapper" action="mylistview.php?list_id=<?=$list['id'] ?>" method="post">
            <input type="hidden" name="list_id" value="<?=$list['id'] ?>">
            <div id="form_organizer">
                <input id="item" type="text" name="item" placeholder="項目">
                <input id="content" type="text" name="content" placeholder="詳細">
                <input id="add_button" type="submit" name="add" value="追加">
            </div>
        </form>
    </div>
</body>
</html>
