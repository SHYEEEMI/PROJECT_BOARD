<?php
    //삭제 성공시 list.php로 이동 
    include_once "db/db_board.php";
    session_start();
    $login_user = $_SESSION["login_user"];
    $i_board = $_GET['i_board'];
    $i_user = $login_user['i_user'];
    $param =[
        "i_board" => $i_board,
        "i_user" => $i_user,
    ]; //아규먼트값을 del_board에게 보냄 

    $result = del_board($param);
    if($result){
    header("Location: list.php");
    }
?>