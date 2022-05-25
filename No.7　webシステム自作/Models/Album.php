<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Album extends Db {
    //private $table = 'albums';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * albumsテーブルに新規のアルバムデータを登録
    */
    public function createAlbum($input) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'INSERT INTO albums (user_id, name, created_at, private) VALUES (:user_id, :name, :created_at, :private)';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':user_id', $input['user_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $sth->bindParam(':created_at', $input['created_at'], PDO::PARAM_STR);
            $sth->bindParam(':private', $input['private'], PDO::PARAM_INT);
            $retention1 = $sth->execute();
            $sql2 = 'SELECT LAST_INSERT_ID() FROM albums WHERE user_id = :user_id';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':user_id', $input['user_id'], PDO::PARAM_INT);
            $retention2 = $sth->execute();
            $album_id = $sth->fetch(PDO::FETCH_ASSOC);
            if ($retention1 || $retention2) {
                $this->dbh->commit();
                return $album_id;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * albumsテーブルとalbumpagesテーブルから指定のアルバムデータを削除
    */
    public function deleteAlbum($id) {
        try {
            $this->dbh->beginTransaction();
            $sql1 = 'DELETE FROM albums WHERE id = :id LIMIT 1';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $retention1 = $sth->execute();
            $sql2 = 'DELETE FROM albumpages WHERE album_id = :album_id';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':album_id', $id, PDO::PARAM_INT);
            $retention2 = $sth->execute();
            if ($retention1 || $retention2) {
                $this->dbh->commit();
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * albumsテーブルから追加表示のアルバムデータを取得
    */
    public function findAdditionalAlbums($count) {
        $sql = 'SELECT a.*, (SELECT p.image FROM albumpages p WHERE p.page_num = 1 AND p.album_id = a.id) AS image FROM albums a JOIN albumpages p ON a.id = p.album_id WHERE a.private = 1 GROUP BY a.id ORDER BY a.created_at DESC LIMIT :count, 4';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':count', $count, PDO::PARAM_INT);
        $sth->execute();
        $albums = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $albums;
    }

    /**
    * albumsテーブルから指定user_idのアルバムデータを取得
    */
    public function findAdditionalMyAlbums($user_id, $count) {
        $sql = 'SELECT a.*, (SELECT p.image FROM albumpages p WHERE p.page_num = 1 AND p.album_id = a.id) AS image FROM albums a JOIN albumpages p ON a.id = p.album_id WHERE a.user_id = :user_id AND a.private = 1 GROUP BY a.id ORDER BY created_at ASC LIMIT :count, 4';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->bindParam(':count', $count, PDO::PARAM_INT);
        $sth->execute();
        $albums = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $albums;
    }

    /**
    * albumsテーブルから指定idのアルバムデータを取得
    */
    public function findAlbumById($id) {
        $sql = 'SELECT * FROM albums WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $album = $sth->fetch(PDO::FETCH_ASSOC);
        return $album;
    }

    /**
    * albumsテーブルから初期表示のアルバムデータを取得
    */
    public function findInitialAlbums() {
        $sql = 'SELECT * FROM albums WHERE private = 1 GROUP BY id ORDER BY created_at DESC LIMIT 12';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $bodies = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT p.album_id, p.image FROM albumpages p JOIN albums a ON a.id = p.album_id WHERE p.page_num = 1';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $images = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
        $albums = [
            'bodies' => $bodies,
            'images' => $images
        ];
        return $albums;
    }

    /**
    * albumsテーブルから指定user_idのアルバムデータを取得
    */
    public function findInitialMyAlbums($user_id) {
        $sql = 'SELECT * FROM albums WHERE user_id = :user_id ORDER BY created_at ASC LIMIT 7';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $bodies = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sql = 'SELECT p.album_id, p.image FROM albumpages p JOIN albums a ON a.id = p.album_id WHERE a.user_id = :user_id AND p.page_num = 1';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $images = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
        $albums = [
            'bodies' => $bodies,
            'images' => $images
        ];
        return $albums;
    }

    /**
    * albumsテーブルから検索アルバムデータを取得
    */
    public function findSearchedAlbums($input) {
        if($input['object'] == 'album_name') {
            $sql = 'SELECT a.*, (SELECT p.image FROM albumpages p WHERE p.page_num = 1 AND p.album_id = a.id) AS image FROM albums a JOIN albumpages p ON a.id = p.album_id WHERE a.name LIKE :key_word AND a.private = 1 GROUP BY a.id ORDER BY a.created_at DESC';
        } else if($input['object'] == 'caption') {
            $sql = 'SELECT a.*, (SELECT p.image FROM albumpages p WHERE p.page_num = 1 AND p.album_id = a.id) AS image FROM albums a JOIN albumpages p ON a.id = p.album_id WHERE p.caption LIKE :key_word AND a.private = 1 GROUP BY a.id ORDER BY a.created_at DESC';
        }
        $sth = $this->dbh->prepare($sql);
        $input['key_word'] = '%'.$input['key_word'].'%';
        $sth->bindParam(':key_word', $input['key_word'], PDO::PARAM_STR);
        $sth->execute();
        $albums = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $albums;
    }
}
?>
