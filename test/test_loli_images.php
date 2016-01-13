<?php
    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    if($link)
    {
        mysql_select_db(SAE_MYSQL_DB, $link);
        
        $sql = "select count(*) from loli_images";
        $result = mysql_query($sql);
        $rand_id_max = mysql_fetch_row($result);
        
        $random_id = rand(1, $rand_id_max[0]); 
        
        $sql = "SELECT loli_images_url FROM loli_images WHERE loli_images_id='$random_id'";
        $result = mysql_query($sql);//执行sql语句
        $row = mysql_fetch_row($result);                
    } 
	echo $row[0];

    $img = new SaeImage();
    $img->setData( $row[0] );
    $img->resize(200); // 等比缩放到200宽
if ($new_data === false)
         var_dump($img->errno(), $img->errmsg());
	print_r($img);
        echo "===============================================\n";
        echo $row[0];
?>