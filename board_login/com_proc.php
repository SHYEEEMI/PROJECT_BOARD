<?php
    include_once "db/db_board.php"; 
    session_start();

    $i_board = $_POST['i_board'];
    $ctnt = $_POST['ctnt'];

    $login_user = $_SESSION['login_user'];
    $i_user = $login_user['i_user'];
  
    $param = [
            "i_board" => $i_board,
            "i_user" => $i_user,
            "ctnt" => $ctnt
        ];
    $result = ins_comment($param);
    if($result){
        header("Location:detail.php?i_board=${i_board}");
    } 
?>