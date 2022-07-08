<?php
    include_once "db/db_board.php";
   
    session_start(); //세션 사용
    $nm = "";
    
    $page =1; 
    if(isset($_GET["page"]))
    { 
        $page = intval($_GET["page"]); //intval = 문자열을 정수형으로 변환함수 
    }
    if(isset($_SESSION['login_user']))
    {
        $login_user = $_SESSION['login_user'];
        $nm = $login_user['nm'];
    }
    $row_count = 20;
    $param =[
        "row_count" => $row_count,
        "start_idx" => ($page - 1) * $row_count
    ];
    
    $paging_count = sel_paging_count($param);
    $list = sel_board_list($param); // sel_board_list 결과값이 $list에 담김 
    //검색버튼을 눌렀거나, 검색어가 존재한다면
    if(isset($_POST['search_input_txt'])&& $_POST['search_input_txt'] != ""){
        //파라미터에 추가해준다
        $param += [
            "search_select" => $_POST["search_select"], //select박스 value값
            "search_input_txt" => $_POST["search_input_txt"] //검색어
        ];
        //DB조회 전달 후 결과 list를 받아온다
        $list = search_board($param);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="common.css">
    <title>리스트</title>
</head>
<body>
    <div id ="container">
        <header>
            <?=isset($_SESSION["login_user"]) ? "<div>". $nm . "님 환영합니다.</div>" : "" ?>
            <div class=log>
                <a href="list.php">리스트</a>
                <?php if(isset($_SESSION["login_user"])) { ?>
                    <a href="write.php">글쓰기</a>
                    <a href="logout.php">로그아웃</a>
                    <a href="profile.php">프로필
                            <?php
                                $session_img = $_SESSION["login_user"]["profile_img"];
                                $profile_img = $session_img == null ? "profile.jpg" : $_SESSION["login_user"]["i_user"]. "/" .$session_img; 
                            ?>
                            <div class="circular__img circular__size40">
                                <img src="/board_login/img/profile/<?=$profile_img?>" width="100">
                        </div>
                    </a>
                <?php } else { ?>
                    <a href="login.php">로그인</a>
                <?php } ?>                
            </div>
        </header>
        <main>
            <h1>리스트</h1>
            <table>  
                <thead>
                    <tr>
                        <th>글번호</th>
                        <th>제목</th>
                        <th>글쓴이</th>
                        <th>등록일시</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $item) { ?>
                        <tr>
                            <td><?=$item["i_board"]?></td>
                            <td class="clo"><a href="detail.php?i_board=<?=$item["i_board"]?>&page=<?=$page?>"><?=$item["title"]?></a></td>
                            <td><?=$item["nm"]?>
                                <?php
                                    $profile_img = $item["profile_img"] == null ? "profile.jpg" : $item["i_user"]. "/" .$item["profile_img"]; 
                                ?>
                                <div class="circular__img circular__size40">
                                    <img src="/board_login/img/profile/<?=$profile_img?>">
                                </div>
                            </td>
                            <td><?=$item["created_at"]?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="num">
                <?php
                    for($i=1; $i<=$paging_count; $i++) { ?>
                    <span class="<?=$i===$page ? "pageSelected" : ""?>">
                        <a href="list.php?page=<?=$i?>"><?=$i?></a>
                    </span>
                <?php } ?>
            </div>
            <form id="search" method="POST" action="list.php"> 
                <div class="sear">
                    <div class="select">
                        <select name="search_select">
                            <option value="search_writer">작성자</option>
                            <option value="search_title">제목</option>
                            <option value="search_ctnt">제목+내용</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="search_input_txt">
                        <input type="submit" value="검색">
                    </div>
                </div>
            </form>
        </main>
    </div>
</body> 
</html>