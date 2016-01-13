<meta charset=utf-8>
<?php

	$keyword = "的";

    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if($link)
    {
        mysql_select_db(SAE_MYSQL_DB, $link);
        
        $sql = "select count(*) from random_response";
        $result = mysql_query($sql);
        $rand_id_max = mysql_fetch_row($result);
        echo $rand_id_max[0]."<br />";
        //$random_id = rand(1, $rand_id_max[0]); 
        $random_id = rand(1, 5); 
        
        //$sql = "SELECT random_response_content FROM random_response WHERE random_response_id='$random_id'";
        //$sql = "SELECT random_response_content FROM random_response WHERE random_response_quiz like '%$keyword%'";
        $sql = "SELECT count(random_response_id) FROM random_response WHERE random_response_content LIKE  '%的%'";
        $sql = "SELECT random_response_id FROM random_response WHERE random_response_content LIKE  '%的%'";
        //$sql = "SELECT count(*) from ".$sql;
        $result = mysql_query($sql);//执行sql语句
        $row = mysql_fetch_row($result);
        echo $row[0]."<br />";
        print_r($row);
            
        //$result = mysql_query($sql);//执行sql语句
        //$row = mysql_fetch_row($result);
        //$contentStr = $row[0];
        //if ($row[0] == "")
            //  $contentStr = "啥都没有";
        //echo $contentStr;                                    
    }  
?>