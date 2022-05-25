<?php
require_once(ROOT_PATH .'/Models/Db.php');

class MyList extends Db {
    //private $table = 'mylists';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * mylistsテーブルに新規のリストを登録
    */
    public function createMyList($input) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'INSERT INTO mylists (user_id, name) VALUES (:user_id, :name)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':user_id', $input['user_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $retention = $sth->execute();
            if ($retention) {
                $this->dbh->commit();
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * mylistsテーブルとmylistrecordsテーブルから指定idのリストデータを削除
    */
    public function deleteMyList($id) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'DELETE FROM mylists WHERE id = :id LIMIT 1';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $retention1 = $sth->execute();
            $sql2 = 'DELETE FROM mylistrecords WHERE list_id = :list_id';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':list_id', $id, PDO::PARAM_INT);
            $retention2 = $sth->execute();
            if ($retention1 || $retention2) {
                $this->dbh->commit();
                return true;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * mylistsテーブルから指定idのリストデータを取得
    */
    public function findMyListById($id) {
        $sql = 'SELECT * FROM mylists WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $list = $sth->fetch(PDO::FETCH_ASSOC);
        return $list;
    }

    /**
    * mylistsテーブルから指定のuser_idのリストデータを取得
    */
    public function findMyLists($user_id) {
        $sql = 'SELECT * FROM mylists WHERE user_id = :user_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $lists = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $lists;
    }
}
?>
