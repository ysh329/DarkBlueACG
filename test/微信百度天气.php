<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj -> responseMsg();//每次重新调整token，这里要将responseMsg改为valid
date_default_timezone_set("Asia/Shanghai");

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this -> checkSignature())
		{
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
        
		if (!empty($postStr))
        {
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj -> FromUserName;
                $toUsername = $postObj -> ToUserName;
            	$type = $postObj -> MsgType;
            	$customevent = $postObj -> Event;
			
                $keyword = trim($postObj -> Content);
                $time = time();
            
                $textTpl = "<xml>
                              <ToUserName><![CDATA[%s]]></ToUserName>
                              <FromUserName><![CDATA[%s]]></FromUserName>
                              <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[news]]></MsgType>
                                <ArticleCount>3</ArticleCount>
                                <Articles>
                                  <item>
                                  <Title><![CDATA[%s]]></Title> 
                                  <Description><![CDATA[s]]></Description>
                                  <PicUrl><![CDATA[picurl]]></PicUrl>
                                  <Url><![CDATA[url]]></Url>
                                  </item>
                
                                  <item>
                                  <Title><![CDATA[%s]]></Title> 
                                  <Description><![CDATA[s]]></Description>
                                  <PicUrl><![CDATA[picurl]]></PicUrl>
                                  <Url><![CDATA[url]]></Url>
                                  </item>
                                </Articles>
                              <FuncFlag>1</FuncFlag>
                            </xml>"; 
                
                  if ($MsgType == "location")
                  {
                    $url = "http://api.map.baidu.com/telematics/v2/weather?location={$w},{$j}&ak=6eeplpjtjf18kHHAKj3ckm8z";
                  }
                  elseif ($MsgType == "text")
                  {
                    $url = "http://api.map.baidu.com/telematics/v2/weather?location={$keyword}&ak=6eeplpjtjf18kHHAKj3ckm8z";
                  }
                  else
                  {
                    echo "";
                  }
                  $fa = file_get_contents($url);
                  $fa = simplexml_load_string($fa);
                  $city = $f -> currentCity;
                  $da1 = $f -> results -> result[0] -> date;
                  $da2 = $f -> results -> result[1] -> date;
                  $da3 = $f -> results -> result[2] -> date;
                  $w1 = $f -> results -> result[0] -> weather;
                  $w2 = $f -> results -> result[1] -> weather;
                  $w3 = $f -> results -> result[2] -> weather;
                  $p1 = $f -> results -> result[0] -> wind;
                  $p2 = $f -> results -> result[1] -> wind;
                  $p3 = $f -> results -> result[2] -> wind;
                  $q1 = $f -> results -> result[0] -> temperature;
                  $q2 = $f -> results -> result[1] -> temperature;
                  $q3 = $f -> results -> result[2] -> temperature;
                  $d1 = $city.$da1.$w1.$p1.$q1;
                  $d2 = $city.$da2.$w2.$p2.$q2;
                  $d3 = $city.$da3.$w3.$p3.$q3;
                  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $d1, $d2, $d3);
                  echo $resultStr;

        }
        else //keyword为空
        {
        	echo "";
        	exit;
        }
    }
    
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
0
?>