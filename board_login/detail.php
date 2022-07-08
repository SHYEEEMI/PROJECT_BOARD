<?php
    include_once 'db/db_board.php';
    session_start();
    if(isset($_SESSION["login_user"])){
        $login_user = $_SESSION["login_user"]; 
    } 
    $i_board = $_GET['i_board'];
    $page = $_GET['page'];
    $i_nm = $login_user['nm'];
    $param =[
        "i_board" => $i_board,
    ];
    $item = sel_board($param);
    $next_board = sel_next_board($param);
    $prev_board = sel_prev_board($param); 

    $com = comment($param);

?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $item['title'] ?></title>
    <link rel="stylesheet" href="detail.css">
</head>
<body>
    <div class="list"><a href="list.php?page=<?=$page?>">리스트</a></div>
    <div class="pn">
        <?php if($pre_board !== 0){?>
            <span class="btn prev"><a href="detail.php?i_board=<?=$prev_board?>">이전글</a></span>
        <?php } ?>

        <?php if($next_board !== 0){?>
            <span class="btn next"><a href="detail.php?i_board=<?=$next_board?>">다음글</a></span>
        <?php } ?>
    </div>
    <?php if(isset($_SESSION["login_user"]) && $login_user["i_user"] === $item["i_user"]) { ?>
        <button class="btn2 del" onclick="isDel();">삭제</button>
        <a href="mod.php?i_board=<?=$i_board?>"><button class="btn2 mod">수정</button></a>
    <?php } ?>
    <div class="ctnt1">
        <div>글쓴이 : <?= $item["nm"] ?> </div>
        <div>등록일시 : <?= $item["created_at"] ?> </div>
        <div>제목 : <?=$item["title"]?></div>
        <div><div class="d1"><?=$item["ctnt"] ?>
        <?php 
        if ($item["file"] !== ""){
            echo "<img src='upload/".$item['file']."'>";
        }
        ?>
        </div>
        <div>수정일시 : <?=$item["updated_at"] ?></div>
    </div>
    <div>
            <div class="com1">댓글</div>
                <?php
                    foreach($com as $row) {?>
                        <div class="comm">
                            <div><?=$row["ctnt"]?></div>
                            <div id="comment">
                                <div><?=$row["nm"]?></div>
                                <div><?=$row["updated_at"]?></div>
                            </div>
                        </div>
                    <?php }  ?>  
                </div>
                
                    <form action="com_proc.php" method="post">
                        <div class="c cn">
                            <div class="nm"><div class="i_nm"><?=$i_nm?></div>
                            <div><input type="hidden" name="i_board" value=<?=$i_board?>></div>
                            <div><textarea id="ctnt" name="ctnt" placeholder="내용"></textarea></div></div>
                            <div><input id="sub" type="submit" value="댓글등록"></div>
                        </div>
                    </form>
                
    <script>
        function isDel()//소괄호 있으면 함수일 확률 높음 
        {
            console.log('isDel 실행 됨!!');
            //const -> 상수 
            if(confirm('삭제하시겠습니까?')){
                location.href= "del.php?i_board=<?=$i_board?>";
            } // confirm 삭제하시겠습니까?는 trun return , false return 
        }
    </script>
</body>
</html>