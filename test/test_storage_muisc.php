<meta charset=utf-8>
<?php
	include_once('saestorage.class.php');
    $stor = new SaeStorage();
    $file_url = $stor->getUrl("music","爱在西元前.mp3");
	echo $file_url;
//http://darkblueacgstandby-music.stor.sinaapp.com/jay/爱在西元前.mp3
	$pic_url = "http://darkblueacgstandby-darkblue.stor.sinaapp.com/images/loli/".$random_id.".jpg";
	$domain = "darkblue";
	$path = "darkblue/images/loli";
	$pic_num = getFilesNum($domain, $path);
	echo $pic_num;

    // 列出 Domain 下所有路径以photo开头的文件
    $stor = new SaeStorage();
    
    $num = 0;
//string $domain, [string $prefix = NULL], [int $limit = 10], [int $offset = 0]
    while ( $ret = $stor->getList("darkblue", NULL, 100, $num ) ) {
        foreach($ret as $file) {
            // echo "{$file}\n";
            $num ++;
        }
    }
    
// echo "\nTOTAL: {$num} files\n";
?>