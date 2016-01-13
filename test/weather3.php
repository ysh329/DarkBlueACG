<?php
/**
*wechat php test
*/
//define your token
  define("TOKEN", "jiekou");
  $wechatObj = new wechatCallbackapiTest();
  $wechatObj -> responseMsg();

  class wechatCallbackapiTest
  {
    public function valid()
    {
      $echoStr = $_GET["echostr"];

      //valid signature, option
      if ($this -> checkSignature())
      {
        echo $echoStr;
        exit;
      }
    }
  }

public function responseMsg()
{
//get post data, May be due to the different enviroments
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//extract post data
if (!empty($postStr))
$postObj = simplexml_load_string($postStr, "SimpleXMLElement", LIBXML_NOCDATA);
$fromUsername = $postObj -> FromUserName;
$toUsername = $postObj -> ToUserName;
$MsgType = $postObj -> MsgType;
$keyword = trim($postObj -> Content);
$j = $postObj -> Location_X;
$w = $postObj -> Location_Y;
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
    $url = "http://api.map.baidu.com/telematics/v2/weather?location={$w},{$j}&ak=";
  }
  elseif ($MsgType == "text")
  {
    $url = "http://api.map.baidu.com/telematics/v2/weather?location={$keyword}&ak=";
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

    }else
    {
      echo"";
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