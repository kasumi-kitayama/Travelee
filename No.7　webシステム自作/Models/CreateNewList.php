<?php
// require_once(ROOT_PATH .'/Models/Db.php');


//
// public function __construct($dbh = null) {
//   parent::__construct($dbh);
// }







// /**
// * mylistsテーブルに新規のリストデータを登録
// */
    // $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    // try {
    //     $this->dbh->beginTransaction();
    //     $sql1 = 'INSERT INTO mylists (user_id, name) VALUES (:user_id, :name)';
    //     $sth = $this->dbh->prepare($sql1);
    //     $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $sth->bindParam(':name', $name, PDO::PARAM_STR);
    //     $retention1 = $sth->execute();
    //     $sql2 = 'SELECT LAST_INSERT_ID() FROM mylists WHERE user_id = :user_id';
    //     $sth = $this->dbh->prepare($sql2);
    //     $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $retention2 = $sth->execute();
    //     $list_id = $sth->fetch(PDO::FETCH_ASSOC);
    //     $sql3 = 'SELECT list_id, name FROM mylists WHERE list_id = :list_id';
    //     $sth = $this->dbh->prepare($sql3);
    //     $sth->bindParam(':list_id', $list_id, PDO::PARAM_INT);
    //     $retention3 = $sth->execute();
    //
    //
    //     // $list = array();
    //     // while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    //     //     $list[] = array(
    //     //         'list_id' => $row['list_id'],
    //     //         'name' => $row['name']
    //     //     );
    //     // }
    //     $row = $sth->fetch(PDO::FETCH_ASSOC);
    //     if ($retention1 || $retention2 || $retention3) {
    //         $this->dbh->commit();
    //     }
    // } catch (PDOException $e) {
    //     echo 'エラーが発生しました：'.$e->getMessage();
    //     $this->dbh->rollBack();
    // }
    //




// ↓ ifの中に入れる？
    // $list = array();
    // while($row)) {
    //     $list[] = array(
    //         'list_id' => $row['list_id'],
    //         'name' => $row['name']
    //     );
    // }



    // $list = array(
    //     'list_id' => $row['list_id'],
    //     'name' => $row['name']
    // );
    $list = array('name' => $name);
    // ヘッダーを指定することによりjsonの動作を安定させる
    header('Content-type: application/json; charset=UTF-8');
    // htmlへ渡す配列をjsonに変換する
    echo json_encode($list);
?>
