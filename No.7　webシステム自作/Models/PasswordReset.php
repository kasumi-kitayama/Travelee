<?php
require_once(ROOT_PATH .'/Models/Db.php');

class PasswordReset extends Db {
    //private $table = 'passwordreset';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * passwordresetテーブルで指定アドレスのトークンを発行/更新
    */
    public function createToken($email) {
        // 登録済みのアドレスか確認
        $sql = 'SELECT * FROM users WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $email, PDO::PARAM_STR);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        if($user) {
            $sql = 'SELECT * FROM passwordreset WHERE email = :email';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->execute();
            $created_token = $sth->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'wrong email';
            exit;
        }
        try {
            $this->dbh->beginTransaction();
            if(!$created_token) {
                $sql1 = 'INSERT INTO passwordreset (email, token, sent_at) VALUES(:email, :token, :sent_at)';
            } else {
                $sql1 = 'UPDATE passwordreset SET token = :token, sent_at = :sent_at WHERE email = :email';
            }
            $token = bin2hex(random_bytes(32));
            $sent_at = date('Y-m-d H:i:s');
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':token', $token, PDO::PARAM_STR);
            $sth->bindParam(':sent_at', $sent_at, PDO::PARAM_STR);
            $retention1 = $sth->execute();
            $sql2 = 'SELECT * FROM passwordreset WHERE email = :email';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $retention2 = $sth->execute();
            $reset_data = $sth->fetch(PDO::FETCH_ASSOC);
            if ($retention1 || $retention2) {
                $this->dbh->commit();
                $reset_data = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $email,
                    'token' => $token
                ];
                return $reset_data;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * passwordresetテーブルから指定tokenのデータを取得
    */
    public function findDataByToken($token) {
        $sql = 'SELECT * FROM passwordreset WHERE token = :token';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':token', $token, PDO::PARAM_STR);
        $sth->execute();
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
}
?>
