<?php
    include_once "db.php";

    function ins_user(&$param){

        $uid = $param["uid"];
        $upw = $param["upw"];
        $nm = $param["nm"];
        $gender = $param["gender"];

        $conn = get_conn();
        $sql = 
        "   INSERT INTO t_user 
            (uid, upw, nm, gender)
            VALUES
            ('$uid','$upw', '$nm', $gender) /*gender는 정수여서 따옴표x*/
        ";
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    function sel_user(&$param)
    {
        $uid = $param['uid'];
        $sql = "SELECT i_user, uid, upw, nm, gender, profile_img
                FROM t_user
                WHERE uid = '$uid'
        ";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return mysqli_fetch_assoc($result);
    }
    //
    function upd_profile_img(&$param){
        $sql = "UPDATE t_user SET profile_img = '{$param["profile_img"]}' 
                where i_user = {$param["i_user"]}";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }
?>