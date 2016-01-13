<meta charset=utf-8>
<?php
        $api = new apibus(); //创建 ApiBus 对象
        $Geo = $api->load( "geoone"); //创建一级地理位置服务对象
        $begin = "116.317245,39.981437";
        $end = "116.328422,40.077796";     
        $drive_route = $Geo->getDriveRoute($begin,$end);
        echo "drive_rote: ";
		echo 'print_r($drive_route);';
        print_r($drive_route);
    
        //错误输出 Tips: 亲，如果调用失败是不收费的 
        if ( $Geo->isError( $drive_route ) )
        { 
            print_r( $drive_route->ApiBusError->errcode ); 
            print_r( $drive_route->ApiBusError->errdesc );
        } 
?>