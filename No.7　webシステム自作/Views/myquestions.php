<?php
if(empty($_SERVER['HTTP_REFERER'])) {
    header('location: index.php');
}
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$params = $controller->myQuestions();
$my_questions = $params['my_questions'];
$others_questions = $params['others_questions'];
$my_answers = $params['my_answers'];
$others_answers = $params['others_answers'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Travelee質問履歴一覧画面</title>
<link rel="stylesheet" type="text/css" href="/css/myquestionsbase.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="myquestions.js"></script>
</head>
<body>
<?php
include (dirname(__FILE__).'/header.php');
?>
    <div id="wrapper">
        <div id="title_wrapper">
            <h1 id="page_title">Q-BOX</h1>
        </div>
        <section id="my_questions_wrapper">
            <h2>送信BOX</h2>
            <?php if(empty($my_questions)): ?>
                <p class="no_question">質問はありません。</p>
            <?php else: ?>
                <ol>
                <?php foreach($my_questions as $question): ?>
                    <li class="question_box">
                        <div class="question_info">
                            <p class="question"><?=$question['content'] ?></p>
                            <a class="link" href="othersalbumview.php?user_id=<?=$question['user_id'] ?>&album_id=<?=$question['album_id'] ?>">↪質問したアルバムを見る</a>
                        </div>
                        <?php if(empty($others_answers['content'][$question['id']])): ?>
                            <div class="answer_box"><span>➥</span><p class="answer">回答待ち</p></div>
                        <?php else: ?>
                            <div class="answer_box"><span>➥</span><p class="answer"><?=$others_answers['content'][$question['id']] ?></p></div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </section>
        <section id="others_questions_wrapper">
            <h2>受信BOX</h2>
            <?php if(empty($others_questions)): ?>
                <p class="no_question">質問はありません。</p>
            <?php else: ?>
                <ol>
                <?php foreach($others_questions as $question): ?>
                    <li class="question_box">
                        <div class="question_info">
                            <p class="question"><?=$question['content'] ?></p>
                            <a class="link" href="myalbumview.php?album_id=<?=$question['album_id'] ?>">↪質問されたアルバムを見る</a>
                        </div>
                        <?php if(empty($my_answers['content'][$question['id']])): ?>
                            <p><?php if(!empty($_SESSION['answer_content'])) echo $_SESSION['answer_content']; ?></p>
                            <p><?php if(!empty($_SESSION['answer'])) echo $_SESSION['answer']; ?></p>
                            <button id="answer_button">回答する</button>
                            <form action="myquestions.php" method="post">
                                <input id="user_id" type="hidden" name="user_id" value="<?=$_SESSION['id'] ?>">
                                <input id="question_id" type="hidden" name="question_id" value="<?=$question['id'] ?>">
                                <div id="form_organizer">
                                    <textarea id="content" type="text" name="content" placeholder="回答"></textarea>
                                    <input id="created_at" type="hidden" name="created_at" value="<?=Date('Y-m-d H:i:s') ?>">
                                    <input id="submit_button" type="submit" name="answer" value="送信">
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="answer_box"><span>➥</span><p class="answer"><?=$my_answers['content'][$question['id']] ?></p></div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
