<meta charset="UTF-8">
<?php
//中国天气网 核心代码
	date_default_timezone_set('PRC');
	echo date("Y-F-jS");	

	echo "查询天气请输入【城市名+空格+天气】。如：北京 天气。";
	$keyword = "北京 天气";
	$keywordsectionArray = explode(" ", $keyword);
    echo "\$keywordsectionArray[0] = ".$keywordsectionArray[0];
    echo "\$keywordsectionArray[1] = ".$keywordsectionArray[1];
	//天气查询 开始
	if ($keywordsectionArray[1] == "天气")
    {
        //城市代码 数据库（_(:з」∠)_） 装载 开始
        $citycode["北京"]="101010100";
        $citycode["西安"]="101110101";
        $citycode["上海"]="101020100";
        $citycode["重庆"]="101040100";
        $citycode["广州"]="101280101";
        //城市代码 数据库（_(:з」∠)_） 装载 结束
        
        switch($keywordsectionArray[0])
        {
            case "北京":
            case "西安":
            case "上海":
            case "重庆":
            case "广州":
            {
                $contentStr = weather($citycode[$keywordsectionArray[0]]);
                echo $contentStr;
				break;
            }
            default:
            {
                $contentStr = "%>_<%暂时我们只能查北京,西安,上海,重庆,广州的天气哦_(:з」∠)_";
                echo $contentStr;
            }
        }
    }

function weather($code = '101110101')
    {
    $url = "http://www.weather.com.cn/data/sk/".$code.".html";
    //	$url = "http://www.weather.com.cn/data/cityinfo/".$code.".html";
    //$url = "http://m.weather.com.cn/data/".$code.".html";
        $str = file_get_contents($url);
        $aa=json_decode($str, true);
        //print_r($aa);
        $aa=json_decode($str, true);
        echo "<br /><br />";
        
        $contentStr = "今天是".$aa["weatherinfo"]["date_y"].$aa["weatherinfo"]["week"]."<br />"."【".$aa["weatherinfo"]['city']."天气播报】"."<br />"."温度指数:".$aa["weatherinfo"]["temp1"]."<br />"."天气情况:".$aa["weatherinfo"]["weather1"]."<br />"."风力指数:".$aa["weatherinfo"]["wind1"]."<br />"."调皮提示:".$aa["weatherinfo"]["index_d"];
        //echo $contentStr;
        return $contentStr;
    }
	//天气查询 结束

?>