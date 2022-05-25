<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Question extends Db {
    //private $table = 'questions';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * questionsテーブルに新規の質問データを登録
    */
    public function addQuestion($input) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'INSERT INTO questions (user_id, album_id, content, created_at) VALUES (:user_id, :album_id, :content, :created_at)';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':user_id', $input['user_id'], PDO::PARAM_INT);
            $sth->bindParam(':album_id', $input['album_id'], PDO::PARAM_INT);
            $sth->bindParam(':content', $input['content'], PDO::PARAM_STR);
            $sth->bindParam(':created_at', $input['created_at'], PDO::PARAM_STR);
            $retension = $sth->execute();
            if($retension) {
                $this->dbh->commit();
                return true;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
      * questionsテーブルから指定ユーザが送信した質問データを取得
    */
    public function findMyQuestions($user_id) {
        $sql = 'SELECT * FROM questions WHERE user_id = :user_id ORDER BY created_at DESC';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $myquestions = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $myquestions;
    }

    /**
      * questionsテーブルから指定ユーザが受信した質問データを取得
    */
    public function findOthersQuestions($user_id) {
        $sql = 'SELECT q.* FROM questions q JOIN albums a ON a.id = q.album_id WHERE a.user_id = :user_id ORDER BY q.created_at DESC';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $othersquestions = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $othersquestions;
    }
}
?>
