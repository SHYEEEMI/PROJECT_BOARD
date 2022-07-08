<?php
    include_once "db/db_user.php";
    session_start();

    define("PROFILE_PATH", "./img/profile/");

    $login_user = &$_SESSION["login_user"]; // &를 주는 순간 얕은 복사 

    var_dump($_FILES);
    if($_FILES["img"]["name"] === "") {
        echo "이미지 없음";
        exit;
    }

    //uuid 생성 
    function gen_uuid_V4(){
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x'
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0x0fff) | 0x4000
        , mt_rand(0, 0x3fff) | 0x8000
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        , mt_rand(0, 0xffff)
        );
    }
    $img_name = $_FILES["img"]["name"];
    $last_index = mb_strrpos($img_name, "."); //mb 사용시 이미지 한글이름 업로드 가능
    // mb_strrpos = 대상 문자열을 앞에서 부터 검색하여 찾고자 하는 문자열이 몇번째 위치에 있는지를 리턴하는 함수
    $ext = mb_substr($img_name, $last_index); //mb_substr = 문자열 자를때 씀 
    
    $target_filenm = gen_uuid_v4() . $ext;
    $target_full_path = PROFILE_PATH . $login_user["i_user"];
    if(!is_dir($target_full_path)){
        mkdir($target_full_path, 0777, true);
    }
    $tmp_img = $_FILES['img']['tmp_name'];
    $imageUpload = move_uploaded_file($tmp_img, $target_full_path ."/" .$target_filenm);
    //$imageUpload =boolean 이고 밑에 if문 때문에 불린이란걸 알수 있음

    if($imageUpload){ //업로드 성공!
        
        //이전에 등록된 프사가 있으면 삭제!  
        if($login_user["profile_img"]){
            $saved_img = $target_full_path . "/" . $login_user["profile_img"];
            if(file_exists($saved_img)){
                unlink($saved_img);
            }
        }

        //DB에 저장!
        $param = [
            "profile_img" => $target_filenm,
            "i_user" => $login_user["i_user"]
        ];

        $result = upd_profile_img($param);
        $login_user["profile_img"] = $target_filenm;
      
        Header("Location: profile.php");
    } else { //업로드 실패!
        echo "업로드 실패";
    }
