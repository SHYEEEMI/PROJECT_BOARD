<?php
    include_once 'db/db_board.php';
    
    $i_board = $_GET["i_board"];
    $param =[
        "i_board" => $i_board,
    ];
    $item =sel_board($param);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 수정</title>
</head>
<body>
    <h1>글수정</h1>
    <a href="detail.php?i_board=<?=$i_board?>"><button>글로 이동</button></a>
    <form action="mod_proc.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="i_board" value="<?=$i_board?>" readonly> 
        <div><input type="text" name="title" placeholder="제목" value="<?=$item['title']?>"></div>
        <div><textarea name="ctnt" placeholder="내용"><?=$item['ctnt']?></textarea></div>
        <div>
            <input type="submit" value="글수정">
            <input type="reset" value="초기화"> 
            <input type="file" value="파일첨부" name="file">
        </div>
    </form>
</body>
</html>