<meta charset="UTF-8">
<?php
	$str = "我的名字叫做甘娜！";
    $seg = new SaeSegment();
    $ret = $seg->segment($str, 1);

    print_r($ret); //输出
?>