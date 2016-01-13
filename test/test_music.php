<meta charset=utf-8>
<?php	
	$keyword = "听 稻香";
	$keywordsectionArray = explode(" ", $keyword);
	echo $keywordsectionArray[1];
	if ($keywordsectionArray[0] == "听")
    {
        $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
        if($link)
        {
            mysql_select_db(SAE_MYSQL_DB, $link);
            $title = $keywordsectionArray[1];
            
            
            $sql = "SELECT title FROM jay_music WHERE title='$title'";//根据keyword找出title
            echo $sql;
            $result = mysql_query($sql);//执行sql语句
            $row = mysql_fetch_row($result);
            print_r($row);
            $title = $row[0];
            echo $title;
            
            if ($title == "")//随机情况下，即没有这首歌
            {
                echo "title啥都没有";
                $sql = "select count(*) FROM jay_music";//先随机出一个id
                $result = mysql_query($sql);
                $rand_id_max = mysql_fetch_row($result);
                $id = rand(1, $rand_id_max[0]);
                
                $sql = "select title FROM jay_music WHERE id=$id";
                $result = mysql_query($sql);
                $row = mysql_fetch_row($result);
                $title = $row[0];
                
                $sql = "select url FROM jay_music WHERE id=$id";
                $result = mysql_query($sql);
                $row = mysql_fetch_row($result);
                $url = $row[0];
                
                $sql = "select author FROM jay_music WHERE id=$id";
                $result = mysql_query($sql);
                $row = mysql_fetch_row($result);
                $author = $row[0];
            }
            else//有这首歌
            {
                $sql = "select author FROM jay_music WHERE title='$title'";//有title根据title，找出对应的author和url
                $result = mysql_query($sql);
                $row = mysql_fetch_row($result);
                $author = $row[0];
                
                $sql = "select url FROM jay_music WHERE title='$title'";//有title根据title，找出对应的author和url
                $result = mysql_query($sql);
                $row = mysql_fetch_row($result);
                $url = $row[0];
            }
        }
    }
	echo "title:".$title."<br />";
	echo "author:".$author."<br />";
	echo "url:".urlencode($url)."<br />";

$url = "http://www.jb51.net"; 
echo urlencode($url); //输出编码后的字符串 
?>