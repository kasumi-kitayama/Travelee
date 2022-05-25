<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$lists = $controller->myList();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Traveleeマイリスト一覧画面</title>
<link rel="stylesheet" type="text/css" href="/css/mylistbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(function() {
    var input = 'off';
    $('#new_list').on('click', function() {
        if(input == 'off') {
            $(this).hide();
            $('form').show();
            $(this).css('border-style','none');
            input = 'on';
        }
    });

    $('#create').on('click', function() {
        var name = $('#name').val();
        if(name.length == 0) {
            alert("リスト名を入力してください。");
        }
    });

    $(document).on('click', function(e) {
        if(!$(e.target).closest('#new_list').length && !$(e.target).closest('#input_name').length && !$(e.target).closest('.mylists').length) {
            if(input == 'on') {
                $('form').hide();
                $('#new_list').show();
                $('#new_list').css('border','1px solid #808080');
                input = 'off';
            }
        }
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
    <div id="wrapper">
        <div id="title_wrapper">
            <h1>マイリスト</h1>
        </div>
        <section id="lists_wrapper">
            <ol>
                <li>
                    <div id="new_list">
                        <div id="add"><p id="plus">+</p></div>
                        <p id="new_list_name">新規作成</p>
                    </div>
                    <form action="mylist.php" method="post">
                        <div id="input_name">
                            <input id="name" type="text" name="name" placeholder="リスト名">
                            <input id="create_button" type="submit" name="create" value="作成">
                        </div>
                    </form>
                </li>
                <?php if(!empty($lists)): ?>
                    <?php foreach($lists as $list): ?>
                    <li>
                        <a class="mylists" href="mylistview.php?list_id=<?=$list['id'] ?>">
                            <p class="list_name"><?=$list['name'] ?></p>
                            <button id="delete" value="mylist.php?delete=<?=$list['id'] ?>">×</button>
                        </a>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
            </ol>
        </section>
    </div>
</body>
</html>
