<?php
    //login.php 에서 넘어오는 값을 적절한 변수에 담는다.
    //변수의 값을 출력.

    include_once 'db/db_user.php';

    $uid = $_POST['uid'];
    $upw = $_POST['upw'];

    // echo $uid , "<br>";
    // echo $upw , "<br>";
    echo "uid : ", $uid, "<br>";
    echo "upw : ", $upw, "<br>";
    
    $param = [
        'uid' => $uid
    ];
    $result = sel_user($param);
    if(empty($result)){
        echo "해당하는 아이디 없음";
        exit; // or die ; 중간에 프로그램이 종료됨 
    }

    echo "i_user : ", $result['i_user'],"<br>";
    echo "pw : ", $result['upw'], "<br>";
    
    //(만약에)비밀번호가 맞으면 "list.php 로 주소 이동"
    //비밀번호가 다르면 "login.php로 주소 이동"

    if($upw === $result['upw']) //로그인 성공!
    {
        session_start();
        $_SESSION['login_user'] = $result; //배열
        header("Location: list.php");
    }
    else {
        header("Location: login.php");
     }
?>