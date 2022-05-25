<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Answer extends Db {
    //private $table = 'answers';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * answersテーブルに新規の回答データを登録
    */
    public function addAnswer($input) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'INSERT INTO answers (user_id, question_id, content, created_at) VALUES (:user_id, :question_id, :content, :created_at)';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':user_id', $input['user_id'], PDO::PARAM_INT);
            $sth->bindParam(':question_id', $input['question_id'], PDO::PARAM_INT);
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
      * answersテーブルから指定ユーザが送信した回答データを取得
    */
    public function findMyAnswers($user_id) {
        $sql = 'SELECT * FROM answers WHERE user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $my_answers = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $my_answers;
    }

    /**
      * answersテーブルから指定ユーザが受信した回答データを取得
    */
    public function findOthersAnswers($user_id) {
        $sql = 'SELECT a.* FROM answers a JOIN questions q ON q.id = a.question_id WHERE q.user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $others_answers = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $others_answers;
    }
}
?>
