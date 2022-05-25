<?php
require_once(ROOT_PATH .'/Models/Db.php');
require_once('C:/PHPMailer/src/PHPMailer.php');
require_once('C:/PHPMailer/src/Exception.php');
require_once('C:/PHPMailer/src/SMTP.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class User extends Db {
    //private $table = 'users';

    public function __construct($dbh = null) {
      parent::__construct($dbh);
    }

    /**
    * usersテーブルの指定ユーザのプロフィールを更新
    */
    public function editProfile($input) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'UPDATE users SET name = :name, image = :image, bio = :bio WHERE id = :id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $sth->bindParam(':image', $input['image_path'], PDO::PARAM_STR);
            $sth->bindParam(':bio', $input['bio'], PDO::PARAM_STR);
            $sth->bindParam(':id', $input['id'], PDO::PARAM_INT);
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
    * usersテーブルから指定idのデータを取得
    */
    public function findUser($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
    * usersテーブルから指定のアドレスのデータを取得
    */
    public function findUserByEmail($input) {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':email', $input['email'], PDO::PARAM_STR);
        $sth->execute();
        $user = $sth->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
    * usersテーブルのパスワードを更新
    */
    public function resetPassword($input) {
        $sql = 'SELECT email FROM passwordreset WHERE token = :token';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':token', $input['token'], PDO::PARAM_STR);
        $sth->execute();
        $email = $sth->fetchColumn();
        try {
            // usersテーブルのパスワードを更新
            $this->dbh->beginTransaction();
            $sql1 = 'UPDATE users SET password = :password WHERE email = :email';
            $sth = $this->dbh->prepare($sql1);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $input['hash'], PDO::PARAM_STR);
            $retention1 = $sth->execute();
            // passwordresetテーブルから使用したレコードを削除
            $sql2 = 'DELETE FROM passwordreset WHERE token = :token LIMIT 1';
            $sth = $this->dbh->prepare($sql2);
            $sth->bindParam(':token', $input['token'], PDO::PARAM_STR);
            $retention2 = $sth->execute();
            if($retention1 || $retention2) {
                $this->dbh->commit();
                return true;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * 各テーブルから指定ユーザの関連データを削除
    */
    public function deleteRelatedData($id) {
        try {
            $this->dbh->beginTransaction();
            // usersテーブルから指定ユーザのデータを削除
            $sql = 'DELETE FROM users WHERE id = :id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $retension1 = $sth->execute();
            // albumsテーブルとalbumpagesテーブルから指定ユーザのデータを削除
            $sql = 'DELETE a, p FROM albumpages p JOIN albums a ON a.id = p.album_id WHERE a.user_id = :user_id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $retension2 = $sth->execute();
            // mylistsテーブルとmylistrecordsテーブルから指定ユーザのデータを削除
            $sql = 'DELETE l, r FROM mylistrecords r JOIN mylists l ON l.id = r.list_id WHERE l.user_id = :user_id';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':user_id', $id, PDO::PARAM_INT);
            $retension3 = $sth->execute();

            if($retension1 || $retension2 || $retension3) {
                $this->dbh->commit();
                return true;
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }

    /**
    * 指定ユーザにメールを送信
    */
    public function sendEmail($reset_data) {
        // インスタンスを生成 (true指定で例外を有効化)
        $phpmailer = new PHPMailer();
        // 文字エンコードを指定
        $phpmailer->CharSet = "utf-8";
        $phpmailer->Encoding = "base64";
        try {
            // デバッグ設定
            //$phpmail->SMTPDebug = 2; //デバッグ出力を有効化 (レベルを指定) -> デバッグ情報を表示しない場合は 0
            //$phpmail->Debugoutput = function($str, $level) { echo 'debug level $level; message: $str<br>'; }
            // SMTPサーバの設定
            $phpmailer->isSMTP(); // SMTPの使用宣言
            $phpmailer->Host = 'smtp.mailtrap.io'; // SMTPサーバを指定
            $phpmailer->SMTPAuth = true; // SMTP authenticationを有効化
            $phpmailer->SMTPSecure = 'tls'; // 暗号化を有効 (tls or ssl) 無効の場合はfalse
            $phpmailer->Port = 2525; // TCPポートを指定 (tlsの場合は465や587)
            $phpmailer->Username = '4831e222c6e2be'; // SMTPサーバのユーザ名
            $phpmailer->Password = '46c431646e73aa'; // SMTPサーバのパスワード
            // 送受信先設定 (第二引数は省略可)
            $phpmailer->setFrom('travelee@email.com', 'Travelee運営部'); // 送信者 (アドレス, 名前)
            $phpmailer->addAddress($reset_data['email'], $reset_data['name']); // 宛先 (アドレス, 名前)
            //$mail->addReplyTo('replay@example.com', 'お問い合わせ'); // 返信先
            //$mail->addCC('cc@example.com', '受信者名'); // CC宛先
            //$mail->Sender = 'return@example.com'; // Return-path
            // 送信内容設定
            $phpmailer->Subject = "Traveleeパスワード変更用URLをお送りします"; // 件名   //6: 2400:4051:6a3:3800:e9cf:197a:1c5b:3b5c // 4: 192.168.11.7
            $phpmailer->Body = "以下のURLからパスワードがご変更いただけます。URLは24時間有効です。"."\n"."localhost/confirmpassword.php?token=".$reset_data['token']; // メッセージ本文
            //メール送信
            $phpmailer->send();
            return true;
        } catch (Exception $e) {
            return 'メールが送信できませんでした: '.$phpmail->ErrorInfo;
        }
    }

    /**
    * usersテーブルに新規ユーザデータを登録
    */
    public function signUp($input) {
        try {
            $this->dbh->beginTransaction();
            $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)';
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':name', $input['name'], PDO::PARAM_STR);
            $sth->bindParam(':email', $input['email'], PDO::PARAM_STR);
            $sth->bindParam(':password', $input['hash'], PDO::PARAM_STR);
            $retention = $sth->execute();
            if ($retention) {
                $this->dbh->commit();
            }
        } catch (PDOException $e) {
            echo 'エラーが発生しました：'.$e->getMessage();
            $this->dbh->rollBack();
        }
    }
}
?>
