<?php
require_once(ROOT_PATH .'/Models/Album.php');
require_once(ROOT_PATH .'/Models/AlbumPage.php');
require_once(ROOT_PATH .'/Models/Answer.php');
require_once(ROOT_PATH .'/Models/MyList.php');
require_once(ROOT_PATH .'/Models/MyListRecord.php');
require_once(ROOT_PATH .'/Models/PasswordReset.php');
require_once(ROOT_PATH .'/Models/Question.php');
require_once(ROOT_PATH .'/Models/User.php');

session_start();

class Controller {
    private $request;
    private $Album;
    private $AlbumPage;
    private $Answer;
    private $MyList;
    private $MyListRecord;
    private $PasswordReset;
    private $Question;
    private $User;

    public function __construct() {
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;
        $this->Album = new Album();
        $dbh = $this->Album->get_db_handler();
        $this->AlbumPage = new AlbumPage($dbh);
        $this->Answer = new Answer($dbh);
        $this->MyList = new MyList($dbh);
        $this->MyListRecord = new MyListRecord($dbh);
        $this->PasswordReset = new PasswordReset($dbh);
        $this->Question = new Question($dbh);
        $this->User = new User($dbh);
    }

    public function addAlbumPage() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/addalbumpage.php') {
            $_SESSION['album'] = '';
            $_SESSION['album_image'] = '';
          }
        if(isset($this->request['post']['create_album'])) {
            $input = $this->request['post'];
            $error = 0;
            if(empty($input['name'])) {
                $_SESSION['album'] = "アルバム名を入力してください。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $input['created_at'] = date('Y-m-d H:i:s');
            if(empty($input['private'])) {
                $input['private'] = '0';
            } else {
                $input['private'] = '1';
            }
            $album_id = $this->Album->createAlbum($input);
            if($album_id) {
                unset($_SESSION['album']);
            } else {
                $_SESSION['album'] = "アルバムを作成できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $album = [
                'id' => $album_id['LAST_INSERT_ID()'],
                'name' => $input['name']
            ];
            return $album;
        }
        if(isset($this->request['get']['album_id'])) {
            $album_id = $this->request['get']['album_id'];
            $album = $this->Album->findAlbumById($album_id);
            $params = [
                'id' => $album_id,
                'name' => $album['name']
            ];
            return $params;
        }
    }

    public function admin() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/admin.php') {
            $_SESSION['delete'] = '';
        }
        if(isset($this->request['get']['admin'])) {
            $user_id = $this->request['get']['admin'];
            $album_id = $this->request['get']['album_id'];
        }
        if(isset($this->request['get']['delete'])) {
            $album['id'] = $this->request['get']['album_id'];
            $album['page'] = $this->request['get']['delete'];
            $delete = $this->AlbumPage->deleteAlbumPage($album);
            if($delete) {
                $user_id = $this->request['get']['user_id'];
                $album_id = $album['id'];
                unset($_SESSION['delete']);
            } else {
                $_SESSION['delete'] = "ページが削除できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        $album = $this->Album->findAlbumById($album_id);
        $album_pages = $this->AlbumPage->findAlbumPages($album_id);
        $params = [
            'user_id' => $user_id,
            'album' => $album,
            'pages' => $album_pages
        ];
        return $params;
    }

    public function AjaxIndex() {
        if(isset($this->request['post']['count'])) {
            $count = $this->request['post']['count'];
            $albums = $this->Album->findAdditionalAlbums($count);
            return $albums;
        }
    }

    public function AjaxMyPage() {
        if(isset($this->request['post']['count'])) {
            $count = $this->request['post']['count'];
            $albums = $this->Album->findAdditionalMyAlbums($_SESSION['id'], $count);
            return $albums;
        }
    }

    public function completePassword() {
        if(isset($this->request['post']['confirm_password'])) {
            $input = $this->request['post'];
            $error = 0;

            if(empty($input['new_password'] || $input['password_to_check']) || ($input['new_password'] !== $input['password_to_check'])) {
                $_SESSION['different_passwords'] = "同じパスワードを入力してください。";
                $error ++;
            } /*else if() {
                // バリデーション
            }*/
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $input['hash'] = password_hash($input['new_password'], PASSWORD_DEFAULT);
            $reset = $this->User->resetPassword($input);
            if(!$reset) {
                $_SESSION['reset_password'] = "パスワードの更新に失敗しました。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            return $reset;
        }
    }

    public function completeResign() {
        if(!empty($this->request['get']['resign'])) {
            $id = $this->request['get']['resign'];
            $resign = $this->User->deleteRelatedData($id);
            if($resign) {
                session_destroy();
            } else {
                $_SESSION['resign'] = "退会処理に失敗しました。お手数ですがログアウト後再度お試しください。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }

    public function completeSignUp() {
        if(isset($this->request['post']['signup'])) {
            $input = $this->request['post'];
            $input['hash'] = password_hash($input['password'], PASSWORD_DEFAULT);
            $this->User->signUp($input);
        }
    }

    public function confirmPassword() {
        if(empty($_SERVER['HTTP_REFERER'])) {
            $_SESSION['different_passwords'] = '';
            $_SESSION['reset_password'] = '';
        }
        if(isset($this->request['get']['token'])) {
            $token = $this->request['get']['token'];
            $error = 0;
            $data = $this->PasswordReset->findDataByToken($token);
            if($data) {
                $valid_period = (new DateTime())->modify('-24 hour')->format('Y-m-d H:i:s');
                if($data['sent_at'] < $valid_period) {
                    $_SESSION['verify_token'] = "このURLは有効期限切れです。再度以下からメールを送信してください。";
                    $error ++;
                }
            } else {
                $_SESSION['verify_token'] = "このURLは無効です。";
                $error ++;
            }
            if($error !== 0) {
                header('location: resetpassword.php');
                exit;
            }
            return $token;
        }
    }

    public function confirmSignUp() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/signup.php') {
            $_SESSION['signup_error_name'] = '';
            $_SESSION['signup_error_email'] = '';
            $_SESSION['signup_error_password'] = '';
        }
        if(isset($this->request['post']['to_signup'])) {
            $input = $this->request['post'];
            $input['hidden_password'] = str_repeat('*', strlen($input['password']));
            $exists = $this->User->findUserByEmail($input);
            $error = 0;
            if($input['name'] == '') {
                $_SESSION['signup_error_name'] = "ユーザ名は必須入力です。";
                $error ++;
            }
            if($exists) {
                $_SESSION['signup_error_email'] = "このメールアドレスは既に登録されています。";
                $error ++;
            } else if(empty($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['signup_error_email'] = "メールアドレスは必須入力です。正しくご入力ください。";
                $error ++;
            }
            if(empty($input['password'] || $input['password_to_check'])) {
                $_SESSION['signup_error_password'] = "パスワードは必須入力です。";
                $error ++;
            } else if($input['password'] !== $input['password_to_check']) {
                $_SESSION['signup_error_password'] = "同じパスワードを入力してください。";
                $error ++;
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            return $input;
        }
    }

    public function createMyAlbum() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/createmyalbum.php') {
            $_SESSION['album'] = '';
            $_SESSION['album_image'] = '';
        }
    }

    public function editMyAlbum() {
        if(isset($this->request['get'])) {
            $album_id = $this->request['get']['album_id'];
            $page_num = $this->request['get']['page_num'];
            $album = $this->Album->findAlbumById($album_id);
            $page = $this->AlbumPage->findAlbumPage($album_id, $page_num);
            $params = [
                'album' => $album,
                'page' => $page
            ];
            return $params;
        }
    }

    public function editMyList() {
        if(isset($this->request['get'])) {
            $list_id = $this->request['get']['list_id'];
            $id = $this->request['get']['record_id'];
            $list = $this->MyList->findMyListById($list_id);
            $record = $this->MyListRecord->findMyListRecordById($id);
            $mylist = [
                'list' => $list,
                'record' => $record
            ];
            return $mylist;
        }
    }

    public function index() {
        if(isset($this->request['get']['delete'])) {
            $target = $this->request['get']['delete'];
            if($target == 'album') {
                // 管理者ユーザによるアルバム削除
                $album_id = $this->request['get']['album_id'];
                $delete = $this->Album->deleteAlbum($album_id);
                if($delete) {
                    unset($_SESSION['delete']);
                } else {
                    $_SESSION['delete'] = "アルバムを削除できませんでした。";
                    header('location: '.$_SERVER['HTTP_REFERER']);
                    exit;
                }
            } else if($target == 'user') {
                // 管理者ユーザによるユーザ削除
                $user_id = $this->request['get']['user_id'];
                $resign = $this->User->deleteRelatedData($user_id);
                if($resign) {
                    unset($_SESSION['delete']);
                } else {
                    $_SESSION['delete'] = "ユーザを削除できませんでした。";
                    header('location: '.$_SERVER['HTTP_REFERER']);
                    exit;
                }
            }
        }
        $albums = $this->Album->findInitialAlbums();
        return $albums;
    }

    public function indexinsearch() {
        if(isset($this->request['post']['search'])) {
            $input = $this->request['post'];
            $albums = $this->Album->findSearchedAlbums($input);
            $params = [
                'albums' => $albums,
                'search' => $input
            ];
            return $params;
        }
    }

    public function myAlbumView() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/myalbumview.php') {
            $_SESSION['edit_album_name'] = '';
            $_SESSION['edit_album_image'] = '';
            $_SESSION['edit_album'] = '';
            $_SESSION['delete_album_page'] = '';
        }
        if(isset($this->request['post']['add_page'])) {
            $input = $this->request['post'];
            $error = 0;
            $last_page = $this->AlbumPage->findLastPage($input['album_id']);
            $input['page_num'] = $last_page + 1;
            // ファイルがあるか
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {
                // 拡張子が画像形式か
                $allow_ext = array('jpg', 'jpeg', 'png');
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($file_ext), $allow_ext)) {
                    $_SESSION['album_image'] = "画像ファイルを添付してください。";
                    $error ++;
                }
                // ファイルを保存する
                $upload_dir = 'C:/MAMP/htdocs/No.7　webシステム自作/public/album_img/';
                $input['image_path'] = date('YmdHis').basename($_FILES['image']['name']);
                if(!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir.$input['image_path'])) {
                    $_SESSION['album_image'] = "ファイルの保存に失敗しました。";
                    $error ++;
                }
            } else {
                $input['image_path'] = 'no_image.jpg';
            }
            if(empty($input['caption']) && $input['image_path'] == 'no_image.jpg') {
                $_SESSION['add_page'] = "画像とキャプションが空欄です。";
                $error ++;
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER'].'?album_id='.$input['album_id']);
                exit;
            }
            $add = $this->AlbumPage->addAlbumPage($input);
            if($add) {
                $album = $this->Album->findAlbumById($input['album_id']);
                $album_pages = $this->AlbumPage->findAlbumPages($input['album_id']);
                    $album = [
                       'id' => $album['id'],
                       'name' => $album['name'],
                       'pages' => $album_pages
                    ];
                    return $album;
            } else {
                $_SESSION['add_page'] = "ページを追加できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER'].'?album_id='.$input['album_id']);
                exit;
            }
        }
        if(isset($this->request['post']['edit_album'])) {
            $input = $this->request['post'];
            $error = 0;
            $_SESSION['edit_album_name'] = '';
            $_SESSION['edit_album_image'] = '';
            $_SESSION['edit_album'] = '';
            if(empty($input['name'])) {
                $_SESSION['edit_album_name'] = "アルバム名を入力してください。";
                $error ++;
            }
            if(empty($input['private'])) {
                $input['private'] = '0';
            } else {
                $input['private'] = '1';
            }
            // ファイルがあるか
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {
                // 拡張子が画像形式か
                $allow_ext = array('jpg', 'jpeg', 'png');
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($file_ext), $allow_ext)) {
                    $_SESSION['edit_album_image'] = "画像ファイルを添付してください。";
                    $error ++;
                }
                // ファイルを保存する
                $upload_dir = 'C:/MAMP/htdocs/No.7　webシステム自作/public/album_img/';
                $input['image_path'] = date('YmdHis').basename($_FILES['image']['name']);
                if(!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir.$input['image_path'])) {
                    $_SESSION['edit_album_image'] = "ファイルの保存に失敗しました。";
                    $error ++;
                }
            } else if(empty($input['no_image'])) {
                $album_page = $this->AlbumPage->findAlbumPage($input['album_id'], $input['page_num']);
                $input['image_path'] = $album_page['image'];
            }
            if(!empty($input['no_image'])) {
                $input['image_path'] = 'no_image.jpg';
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $edit = $this->AlbumPage->editAlbumPage($input);
            if($edit) {

            } else {
                $_SESSION['edit_album'] = "ページが編集できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $album = $this->Album->findAlbumById($input['album_id']);
            $album_pages = $this->AlbumPage->findAlbumPages($input['album_id']);
            $album = [
               'id' => $album['id'],
               'name' => $album['name'],
               'pages' => $album_pages
            ];
            return $album;
        }
        if(isset($this->request['get']['delete_page_num'])) {
            $album['id'] = $this->request['get']['album_id'];
            $album['page'] = $this->request['get']['delete_page_num'];
            $_SESSION['delete_album_page'] = '';
            $delete = $this->AlbumPage->deleteAlbumPage($album);
            if($delete) {
                $_SESSION['delete_album_page'] = '';
            } else {
              $_SESSION['delete_album_page'] = "ページが削除できませんでした。";
              header('location: '.$_SERVER['HTTP_REFERER']);
              exit;
            }
        }
        if(isset($this->request['get']['album_id'])) {
            $album_id = $this->request['get']['album_id'];
            $album = $this->Album->findAlbumById($album_id);
            $album_pages = $this->AlbumPage->findAlbumPages($album_id);
            $album = [
               'id' => $album['id'],
               'name' => $album['name'],
               'pages' => $album_pages
            ];
            return $album;
        }
    }

    public function myList() {
        if(isset($this->request['post']['create'])) {
            $input['name'] = $this->request['post']['name'];
            $input['user_id'] = $_SESSION['id'];
            $this->MyList->createMyList($input);
        }
        if(isset($this->request['get']['delete'])) {
            $list_id = $this->request['get']['delete'];
            $delete = $this->MyList->deleteMyList($list_id);
            if($delete) {
                unset($_SESSION['delete_mylist']);
            } else {
                $_SESSION['delete_mylist'] = "リストが削除できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        $lists = $this->MyList->findMyLists($_SESSION['id']);
        return $lists;
    }

    public function myListView() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/mylistview.php') {
            $_SESSION['edit_list_name'] = '';
            $_SESSION['edit_list'] = '';
            $_SESSION['delete_record'] = '';
        }
        if(isset($this->request['post']['add'])) {
            $input = $this->request['post'];
            $count = $this->MyListRecord->countRecords($input['list_id']);
            $input['page_num'] = $count + 1;
            $this->MyListRecord->addMyList($input);
            $list_id = $input['list_id'];
        } else if(isset($this->request['post']['edit_list'])) {
            $input = $this->request['post'];
            if(empty($input['name'])) {
                $_SESSION['edit_list_name'] = "リスト名は必須入力です。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $edit = $this->MyListRecord->editMyList($input);
            $list_id = $input['list_id'];
            if($edit) {
                unset($_SESSION['edit_list_name']);
                unset($_SESSION['edit_list']);
            } else {
                $_SESSION['edit_list'] = "リストを編集できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        } else if(isset($this->request['get']['delete'])) {
            $record_id = $this->request['get']['delete'];
            $list_id = $this->request['get']['list_id'];
            $_SESSION['delete_record'] = '';
            $delete = $this->MyListRecord->deleteMyListRecord($record_id);
            if($delete) {
                unset($_SESSION['delete_record']);
            } else {
                $_SESSION['delete_record'] = "リストを削除できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        } else if(isset($this->request['get']['list_id'])) {
            $list_id = $this->request['get']['list_id'];
        }
        $list = $this->MyList->findMyListById($list_id);
        $records = $this->MyListRecord->findMyListRecords($list_id);
        $mylist = [
            'list' => $list,
            'records' => $records
        ];
        return $mylist;
    }

    public function myPage() {
        if(isset($this->request['get']['delete_album_id'])) {
            $album_id = $this->request['get']['delete_album_id'];
            $delete = $this->Album->deleteAlbum($album_id);
            if(!$delete) {
                $_SESSION['delete_album'] = "アルバムが削除できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        if(isset($this->request['post']['signin'])) {
            $input = $this->request['post'];
            $error = 0;
            $_SESSION['validate_signin_email'] = '';
            $_SESSION['verify_signin_password'] = '';
            $_SESSION['email'] = $input['email'];
            $user = $this->User->findUserByEmail($input);
            if($user) {
                if(password_verify($input['password'], $user['password'])) {
                    $_SESSION['signin'] = "on";
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    unset($_SESSION['validate_signin_email']);
                    unset($_SESSION['verify_signin_password']);
                } else {
                    $_SESSION['verify_signin_password'] = "パスワードが違います。";
                    $error ++;
                }
            } else {
              $_SESSION['validate_signin_email'] = "登録されていないメールアドレスです。";
              $error ++;
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        if(isset($this->request['post']['profile'])) {
            $input = $this->request['post'];
            $error = 0;
            $_SESSION['profile_name'] = '';
            $_SESSION['profile_bio'] = '';
            $_SESSION['profile_image'] = '';
            if(empty($input['name'])) {
                $_SESSION['profile_name'] = "ユーザ名は必須入力です。";
                $error ++;
            }
            if(strlen($input['bio']) > 200) {
                $_SESSION['profile_bio'] = "自己紹介は200文字以内で入力してください。";
                $error ++;
            }
            // ファイルがあるか
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {
                // 拡張子が画像形式か
                $allow_ext = array('jpg', 'jpeg', 'png');
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if(!in_array(strtolower($file_ext), $allow_ext)) {
                    $_SESSION['profile_image'] = "画像ファイルを添付してください。";
                    $error ++;
                }
                // ファイルを保存する
                $upload_dir = 'C:/MAMP/htdocs/No.7　webシステム自作/public/profile_img/';
                $input['image_path'] = date('YmdHis').basename($_FILES['image']['name']);
                if(!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir.$input['image_path'])) {
                    $_SESSION['profile_image'] = "ファイルの保存に失敗しました。";
                    $error ++;
                }
            } else {
                $user = $this->User->findUser($_SESSION['id']);
                $input['image_path'] = $user['image'];
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
            $this->User->editProfile($input);
        }
        $user = $this->User->findUser($_SESSION['id']);
        $albums = $this->Album->findInitialMyAlbums($_SESSION['id']);
        $params = [
            'user' => $user,
            'albums' => $albums['bodies'],
            'images' => $albums['images']
        ];
        return $params;
    }

    public function myQuestions() {
        if($_SERVER['HTTP_REFERER'] !== 'http://localhost/myquestions.php') {
            $_SESSION['answer_content'] = '';
            $_SESSION['answer'] = '';
        }
        if(isset($this->request['post']['answer'])) {
            $input = $this->request['post'];
            if(empty($input['content'])) {
                $_SESSION['answer_content'] = "回答を入力してください。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $_SESSION['answer_content'] = '';
            }
            $add = $this->Answer->addAnswer($input);
            if($add) {
                unset($_SESSION['answer']);
            } else {
                $_SESSION['answer'] = "回答を送信できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        $my_questions = $this->Question->findMyQuestions($_SESSION['id']);
        $others_questions = $this->Question->findOthersQuestions($_SESSION['id']);
        $my_answers = $this->Answer->findMyAnswers($_SESSION['id']);
        $sorted_my_answers = array(
            'content' => array_column($my_answers, 'content', 'question_id'),
            'created_at' => array_column($my_answers, 'created_at', 'question_id')
        );
        $others_answers = $this->Answer->findOthersAnswers($_SESSION['id']);
        $sorted_others_answers = array(
            'content' => array_column($others_answers, 'content', 'question_id'),
            'created_at' => array_column($others_answers, 'created_at', 'question_id')
        );
        $params = [
            'my_questions' => $my_questions,
            'others_questions' => $others_questions,
            'my_answers' => $sorted_my_answers,
            'others_answers' => $sorted_others_answers
        ];
        return $params;
    }

    public function othersAlbumView() {
        if(isset($this->request['post']['question'])) {
            $input = $this->request['post'];
            if(empty($input['content'])) {
                $_SESSION['question'] = "質問内容を入力してください。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $_SESSION['question'] = '';
            }
            $input['created_at'] = Date('Y-m-d H:i:s');
            $_SESSION['question'] = '';
            $question = $this->Question->addQuestion($input);
            if(!$question) {
                $_SESSION['question'] = "質問が送信できませんでした。";
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            } else {
                $_SESSION['question'] = '';
            }
        }
        if(isset($this->request['get'])) {
            $album_id = $this->request['get']['album_id'];
            $album = $this->Album->findAlbumById($album_id);
            $pages = $this->AlbumPage->findAlbumPages($album_id);
            $params = [
                'user_id' => $this->request['get']['user_id'],
                'album' => $album,
                'pages' => $pages
            ];
            return $params;
        }
    }

    public function profile() {
        $_SESSION['profile_name'] = '';
        $user = $this->User->findUser($_SESSION['id']);
        return $user;
    }

    public function resetPassword() {
        if(empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== 'http://localhost/resetpassword.php') {
            $_SESSION['wrong_email'] = '';
        }
    }

    public function resign() {
        if(isset($this->request['post']['resign'])) {
            $input = $this->request['post'];
            $error = 0;
            $_SESSION['resign_email'] = '';
            $_SESSION['resign_password'] = '';
            $user = $this->User->findUserByEmail($input);
            if($user) {
                if(password_verify($input['password'], $user['password'])) {
                    $_SESSION['signin'] = 'on';
                    $_SESSION['id'] = $user['id'];
                } else {
                    $_SESSION['resign_password'] = "パスワードが違います。";
                    $error ++;
                }
            } else {
              $_SESSION['resign_email'] = "登録されていないメールアドレスです。";
              $error ++;
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }

    public function selectEditPage() {
        if(isset($this->request['get']['album_id'])) {
            $input = $this->request['get']['album_id'];
            $album = $this->Album->findAlbumById($input);
            $album_pages = $this->AlbumPage->findAlbumPages($input);
            $album = [
                'id' => $album['id'],
                'name' => $album['name'],
                'pages' => $album_pages
            ];
            return $album;
        }
    }

    public function sentEmail() {
        if(isset($this->request['post']['send'])) {
            $email = $this->request['post']['email'];
            $error = 0;
            $_SESSION['reset_password_email'] = '';
            $reset_data = $this->PasswordReset->createToken($email);
            if($reset_data) {
                $mail = $this->User->sendEmail($reset_data);
                if(!$mail) {
                    $_SESSION['reset_password_email'] = "メールが送信できませんでした。";
                    $error ++;
                }
            } else if($token == 'wrong email') {
                $_SESSION['reset_password_email'] = "登録しているメールアドレスを入力してください。";
                $error ++;
            }
            if($error !== 0) {
                header('location: '.$_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }

    public function signIn() {
        if(isset($this->request['get']['signout'])) {
            session_destroy();
        }
    }
}
?>
