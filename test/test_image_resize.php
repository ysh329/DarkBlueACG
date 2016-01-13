<?php
    function getimgsize($oldwidth, $oldheight, $imgwidth, $imgheight)
    {
        //$oldwidth图片原始宽度，$oldheight图片原始高度，$imgwidth新宽度，$imgheight新高度
        //以下判断图片容器若能显示图片，则按原始大小显示图片；
        if ($imgwidth <= $oldwidth && $imgheight <= $oldheight)
        {
            $arraysize = array('width'=>$imgwidth, 'height'=>$imgheight);
            return $arraysize;
        }
        else
        {//若图片容器容不下图片，则开始改变
            $suoxiaowidth = $imgwidth-$oldwidth;
            $suoxiaoheight = $imgheight-$oldheight;
            $suoxiaoheightper = $suoxiaoheight/$imgheight;
            $suoxiaowidthper = $suoxiaowidth/$imgwidth;
            if ( $suoxiaoheightper >= $suoxiaowidthper )
            {
            //以高度为准
                $aftersuoxiaowidth = $imgwidth*(1 - $suoxiaoheightper);
                $arraysize = array('width'=>$aftersuoxiaowidth, 'height'=>$oldheight);
                return $arraysize;
            }
            else
            {
                //以宽度为准
                $aftersuoxiaoheight = $imgheight*(1 - $suoxiaowidthper);
                $arraysize = array('width'=>$oldwidth, 'height'=>$aftersuoxiaoheight);
                return $arraysize;
            }
        }
    }

    $arr = getimagesize("http://hiphotos.baidu.com/%BE%F5%B5%C3%B8%E3/pic/item/8a62e103a18b87d619caa752070828381e30fd77.jpg?v=tbs"); 
	echo $arr[0]."<br />";//宽度
	echo $arr[1]."<br />";//高度
	echo $arr[2]."<br />";
    echo $arr[3]."<br />";
    $strarr = explode("\"",$arr[3]);
//echo $strarr[1];
	$aa=getimagesize("http://hiphotos.baidu.com/%BE%F5%B5%C3%B8%E3/pic/item/8a62e103a18b87d619caa752070828381e30fd77.jpg");
	print_r($aa);
	echo "<br />";
	print_r(getimgsize($aa[0], $aa[1], 320, 240));
//print_r(getimgsize($arr[0], $arr[1], 320,null));
?>
<img src="<?php echo getimgsize($aa[0], $aa[1], 320, 240);?>" width="19" height="19">