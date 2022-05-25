<?php
require_once(ROOT_PATH .'/Models/Db.php');

class AlbumPage extends Db {
    //private $table = 'albumpages';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * albumpagesテーブルに新規のアルバムページデータを登録
    */
    public function addAlbumPage($input) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'INSERT INTO albumpages (album_id, page_num, image, caption) VALUES (:album_id, :page_num, :image, :caption)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':album_id', $input['album_id'], PDO::PARAM_INT);
            $sth->bindParam(':page_num', $input['page_num'], PDO::PARAM_INT);
            $sth->bindParam(':image', $input['image_path'], PDO::PARAM_STR);
            $sth->bindParam(':caption', $input['caption'], PDO::PARAM_STR);
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
    * albumpagesテーブルから指定のページを削除
    */
    public function deleteAlbumPage($album) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'DELETE FROM albumpages WHERE album_id = :album_id AND page_num = :page_num LIMIT 1';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':album_id', $album['id'], PDO::PARAM_INT);
            $sth->bindParam(':page_num', $album['page'], PDO::PARAM_INT);
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
    * albumpagesテーブルの指定page_numのページデータを更新
    */
    public function editAlbumPage($input) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'UPDATE albums SET name = :name, private = :private WHERE id = :id';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':id', $input['album_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $sth->bindParam(':private', $input['private'], PDO::PARAM_INT);
            $retention1 = $sth->execute();
            $sql2 = 'UPDATE albumpages SET page_num = :page_num, image = :image, caption = :caption WHERE album_id = :album_id AND page_num = :page_num';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':album_id', $input['album_id'], PDO::PARAM_INT);
            $sth->bindParam(':page_num', $input['page_num'], PDO::PARAM_INT);
            $sth->bindParam(':image', $input['image_path'], PDO::PARAM_STR);
            $sth->bindParam(':caption', $input['caption'], PDO::PARAM_STR);
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
    * albumpagesテーブルから指定のpage_numのページデータを取得
    */
    public function findAlbumPage($album_id, $page_num) {
        $sql = 'SELECT * FROM albumpages WHERE album_id = :album_id AND page_num = :page_num';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $sth->bindParam(':page_num', $page_num, PDO::PARAM_INT);
        $sth->execute();
        $page = $sth->fetch(PDO::FETCH_ASSOC);
        return $page;
    }

    /**
    * albumpagesテーブルから指定idのアルバムページデータを取得
    */
    public function findAlbumPages($album_id) {
        $sql = 'SELECT a.name as name, p.* FROM albumpages p JOIN albums a ON a.id = p.album_id WHERE a.id = :album_id ORDER BY p.page_num';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $sth->execute();
        $album_pages = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $album_pages;
    }

    /**
    * albumpagesテーブルから指定のアルバムの最後のページ番号を取得する
    */
    public function findLastPage($album_id) {
        $sql = 'SELECT page_num FROM albumpages WHERE album_id = :album_id ORDER BY id DESC LIMIT 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $sth->execute();
        $last_page = $sth->fetch(PDO::FETCH_COLUMN);
        return $last_page;
    }
}
?>
