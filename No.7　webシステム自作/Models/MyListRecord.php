<?php
require_once(ROOT_PATH .'/Models/Db.php');

class MyListRecord extends Db {
    //private $table = 'mylistrecords';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * mylistrecordsテーブルに新規のレコードを追加
    */
    public function addMyList($input) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'INSERT INTO mylistrecords (list_id, page_num, item, content) VALUES(:list_id, :page_num, :item, :content)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':list_id', $input['list_id'], PDO::PARAM_INT);
            $sth->bindParam(':page_num', $input['page_num'], PDO::PARAM_INT);
            $sth->bindParam(':item', $input['item'], PDO::PARAM_STR);
            $sth->bindParam(':content', $input['content'], PDO::PARAM_STR);
            $retention = $sth->execute();
            if ($retention) {
                $this->dbh->commit();
                return true;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * mylistrecordsテーブルから指定idのレコード数を取得
    */
    public function countRecords($list_id) {
        $sql = 'SELECT count(id) FROM mylistrecords WHERE list_id = :list_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $sth->execute();
        $count = $sth->fetch(PDO::FETCH_COLUMN);
        return $count;
    }

    /**
    * mylistrecordsテーブルから指定idのレコードデータを削除
    */
    public function deleteMyListRecord($id) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'DELETE FROM mylistrecords WHERE id = :id LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
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
    * mylistsテーブルとmylistrecordsテーブルの指定idのデータを更新
    */
    public function editMyList($input) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'UPDATE mylists SET name = :name WHERE id = :id';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':id', $input['list_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $retention1 = $sth->execute();
            $sql2 = 'UPDATE mylistrecords SET item = :item, content = :content WHERE id = :id';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':id', $input['record_id'], PDO::PARAM_INT);
            $sth->bindParam(':item', $input['item'], PDO::PARAM_STR);
            $sth->bindParam(':content', $input['content'], PDO::PARAM_STR);
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
    * mylistrecordsテーブルから指定のidのレコードデータを取得
    */
    public function findMyListRecordById($id) {
        $sql = 'SELECT * FROM mylistrecords WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $record = $sth->fetch(PDO::FETCH_ASSOC);
        return $record;
    }

    /**
    * mylistrecordsテーブルから指定のlist_idのすべてのレコードデータを取得
    */
    public function findMyListRecords($list_id) {
        $sql = 'SELECT * FROM mylistrecords WHERE list_id = :list_id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':list_id', $list_id, PDO::PARAM_INT);
        $sth->execute();
        $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    }
}
?>
