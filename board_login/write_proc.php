<?php
    include_once "db/db_board.php"; //DAO

    session_start();
    $title = $_POST['title'];
    $ctnt = $_POST['ctnt'];
    $folder = $_FILES['file'];
    $login_user = $_SESSION['login_user'];
    $i_user = $login_user['i_user'];
  
    $tmpfile =  $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];
    $folder = "upload/".$fileName;
    move_uploaded_file($tmpFile,$folder);

    $param = [
            "i_user" => $i_user,
            "title" => $title,
            "ctnt" => $ctnt,
            "file" => $filename
        ];
    $result = ins_board($param);
    if($result){
        header("Location: list.php");
    } //0 빼고 전부 true
     //t_board에 insert 완료 후 list.php로 이동.
?>