<?php
    include_once "db.php";

    function ins_board(&$param) 
    {
        $i_user = $param["i_user"];
        $title = $param["title"];
        $ctnt = $param["ctnt"];
        $folder = $param["file"];

        $sql = 
        "   INSERT INTO t_board
            (title, ctnt, i_user, file)
            VALUES
            ('$title', '$ctnt', $i_user,'$folder') 
        ";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    function sel_paging_count(&$param)
    {
        // $row_count = $param["row_count"];
        $sql = "SELECT CEIL(COUNT(i_board) / {$param["row_count"]}) as cnt 
                FROM t_board";// count(= 그룹함수) = t_board에서 1줄만 나옴 
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        $row = mysqli_fetch_assoc($result);
        return $row["cnt"];//정수 넘어감 
    }
    //페이징  
    function sel_board_list(&$param) { //() 빈칸은 외부에서로부터 값을 안받는다 
        $start_idx = $param["start_idx"];
        $row_count = $param["row_count"];
        $sql = "SELECT A.i_board, A.title, A.created_at
                     , B.nm, B.i_user, B.profile_img
                  FROM t_board A
            INNER JOIN t_user B
                    ON A.i_user = B.i_user
                    ORDER BY A.i_board desc
                    LIMIT {$param["start_idx"]}, {$param["row_count"]}"; //시작 index값과 몇개 
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    function sel_board(&$param){
        $i_board = $param["i_board"];
        $sql="SELECT A.i_board, A.title, A.ctnt, A.created_at, A.updated_at, B.i_user, B.nm, A.file
        FROM t_board A
        INNER join t_user B ON A.i_user = B.i_user 
        WHERE A.i_board = $i_board";

        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return mysqli_fetch_assoc($result); // mysqli_fetch_assoc 배열 처럼 만들어줌
    }
    //다음글 (최신글)
    function sel_next_board(&$param)
    {
        $i_board = $param["i_board"];
        $sql="SELECT i_board 
              FROM t_board 
              WHERE i_board > $i_board
              ORDER BY i_board
              LIMIT 1";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        $row = mysqli_fetch_assoc($result);
        if($row)
        {
            return $row["i_board"];
        }
        return 0;
    }
    //이전글 (지난글) 
    function sel_prev_board(&$param)
    {
        $i_board = $param["i_board"];
        $sql="SELECT i_board
              FROM t_board
              WHERE i_board < $i_board
              ORDER BY i_board desc
              LIMIT 1";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        $row = mysqli_fetch_assoc($result);
        if($row)
        {
            return $row["i_board"];
        }
        return 0;
    }
    function upd_board(&$param){
        $i_board = $param["i_board"];
        $title = $param["title"];
        $ctnt = $param["ctnt"];
        $i_user = $param["i_user"];
        $updated_at = $param["updated_at"];
        $folder = $param["file"];
       
        $sql = "
            UPDATE t_board
            SET title = '$title', 
            ctnt = '$ctnt',
            file = '$folder',
            updated_at = now()
            WHERE i_board = $i_board
            AND i_user = $i_user
        ";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    function del_board(&$param){
        $i_board = $param["i_board"];
        $i_user = $param["i_user"];
        $sql = "
                DELETE FROM t_board 
                WHERE i_board = $i_board
                AND i_user = $i_user
               ";
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    function search_board(&$param){
        $conn = get_conn();
        $search_select = $param['search_select']; //select선택값
        $search_input_txt = $param['search_input_txt']; //검색어
        $textArray = explode(" ",$search_input_txt); //검색어를 공백으로 나눈다
        $where = []; //sql 검색 시 열(속성) 이름
        $query = "SELECT A.*, B.i_user, B.nm
        FROM t_board A
        INNER join t_user B
		  ON A.i_user = B.i_user 
          WHERE ";

        switch($search_select){
            case "search_writer":
                $where += ["B.nm"];
                break;
            case "search_title":
                $where += ["A.title"];
                break;
            case "search_ctnt":
                $where += ["A.ctnt", "A.title"];
                break;
        }
        for($i = 0; $i < count($textArray); $i++){
            for($j=0; $j <count($where); $j++){
                $query = $query." $where[$j] LIKE '%$textArray[$i]%' ";
                //마지막 검색어가 아니면
                if(($i !== count($textArray) -1) || ($j !== count($where) -1)){ 
                    $query = $query. "OR";
                }
            }  
        }
        $result = mysqli_query($conn, $query);
        mysqli_close($conn);
        return $result;
    }
    function comment(&$param){
        $i_board = $param["i_board"];
        $sql="SELECT A.*,B.nm
        FROM t_reply A
        INNER JOIN t_user B ON A.i_user = B.i_user
        WHERE A.i_board = $i_board";

        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }
    function ins_comment(&$param){
        $i_board = $param["i_board"];
        $i_user = $param["i_user"];
        $ctnt = $param["ctnt"];

        $sql = "INSERT INTO t_reply(i_board, i_user, ctnt) 
        VALUES ($i_board, $i_user, '$ctnt')";

        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);

        return $result;
    }
    
?>