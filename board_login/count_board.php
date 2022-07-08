<?php
    include_once "";
    //DB와 커넥션시도하여 connection 객체 얻어오기
    function get_conn() {
        define("URL", "localhost");
        define("USERNAME", "root");
        define("PASSWORD", "506greendg@");
        define("DB_NAME", "board_login");
        return mysqli_connect(URL, USERNAME, PASSWORD, DB_NAME);
    }
    $connection = get_conn();
    //오늘 날짜 정보를 가져옴
    $YY = date('Y');//년
    $MM = date('m');//월
    $DD = date('d');//일
    //2014-06-15
    $dat = $YY."-".$MM."-".$DD;
    //오늘 날짜 정보를 DB에서 조회한다
    $sql = "SELECT * FROM count_db WHERE redate = '$dat'";
    //쿼리 전송
    $result = mysqli_query($connection, $sql);
    //결과 집합을 받아온다
    $list = mysqli_num_rows($result);

    if(!$list){ //아무도 들어온 적이 없어서 date정보가 없을 경우
        $count = 0; //count = 0
    }
    else{ //누군가가 들어온 적이 있어서 date정보가 존재할 경우 
        $row = mysqli_fetch_array($result);
        $count = $row['count']; //현재 날짜의 count값을 가져온다
    }
    if($count === 0){
        $count++;
        //오늘 날짜로 새로운 count값을 추가한다
        $sql = "INSERT INTO count_db VALUES  ($count, '$dat')";
    }
    else{
        $count++;
        //오늘 날짜의 기존 count값을 변경시킨다
        $sql ="UPDATE count_db SET count = $count WHERE redate = '$dat'";
    }
    mysqli_query($connection, $sql);
    //총(Total) 조회수 - 모든 COUNT값을 sum()적용 
    $totalQuery = "SELECT SUM(count)as cnt FROM count_db";
    $totalCount = mysqli_fetch_array(mysqli_query($connection, $totalQuery))[0];
    mysqli_close($connection);

    echo "<br><center><h2> 방문자 수 통계입니다 </h2><hr>";
    echo "[ Today : <font color = red>";
    echo $count;
    echo "명</font>]<br>";

    echo "[ Total : <font color = blue>";
    echo $totalCount;
    echo "명</font>] <br>";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>방문자수 통계</title>
</head>
<body>
    
</body>
</html>