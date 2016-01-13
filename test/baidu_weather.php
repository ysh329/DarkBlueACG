        <meta charset="UTF-8">
<?php
	date_default_timezone_set('PRC');
	//echo date("H:i:s",time());;	//显示当前时间

	$keyword = "西安";
    $url = "http://api.map.baidu.com/telematics/v2/weather?location={$keyword}&ak=6eeplpjtjf18kHHAKj3ckm8z";
    $fa = file_get_contents($url);
	//echo "<br /><br />".$fa;
    $fa = simplexml_load_string($fa);
	//echo "<br /><br />".$fa;
    $city = $fa -> currentCity;
	//echo $fa;
    $da1 = $fa -> results -> result[0] -> date;
    $da2 = $fa -> results -> result[1] -> date;
    $da3 = $fa -> results -> result[2] -> date;
	
    $w1 = $fa -> results -> result[0] -> weather;
    $w2 = $fa -> results -> result[1] -> weather;
    $w3 = $fa -> results -> result[2] -> weather;

    $p1 = $fa -> results -> result[0] -> wind;
    $p2 = $fa -> results -> result[1] -> wind;
    $p3 = $fa -> results -> result[2] -> wind;

    $q1 = $fa -> results -> result[0] -> temperature;
    $q2 = $fa -> results -> result[1] -> temperature;
    $q3 = $fa -> results -> result[2] -> temperature;

	$picurl1 = $fa -> results -> result[0] -> dayPictureUrl;
	$picurl2 = $fa -> results -> result[1] -> dayPictureUrl;
	$picurl3 = $fa -> results -> result[2] -> dayPictureUrl;

	$d1 = "【".$city."今日"."天气】<br />".$da1.$w1.$p1.$q1."<br />";
    $d2 = $da2.$w2.$p2.$q2."<br />";
    $d3 = $da3.$w3.$p3.$q3."<br />";

	$contentStr = $d1.$d2.$d3;
	//echo $contentStr;
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $d1, $d2, $d3);
	echo $resultStr;
?>