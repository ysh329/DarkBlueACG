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
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
							</xml>";
            	switch($type)
                {
                    case "event"://接收到事件信息，此处是关注（订阅）后的自动回复内容
					{
						if ($type == "event" and $customevent == "subscribe")
						{
							$msgType = "text";
							$contentStr = "小盆友萌好ヽ(ˋ▽ˊ)ノ  ，欢迎大家关注DarkBlue。我是呼啦呼啦的调皮的上升气流君[调皮]。\n==================\n大家不要怀疑为啥这个微信号这么奇怪（DarkBlue），因为它来自一款galgame，让身为气流君的我久久不能忘怀(｡・`ω´･)。\n==================\n要问窝这是哪里，窝会告诉泥：这里是满满的节操[玫瑰]，这里是草泥马围观的圣地[敲打]，这里是大草原[拥抱]……\n==================\n啊拉啊拉，这里就是ACG的伊甸园，气流君会给泥推送【最新的游戏，动漫，二次元的咨询消息，以及各种浮力？！๑´ڡ`๑ 】，还有一些福利哦不要错过[调皮]。miss u[飞吻]~~\n==================\n千万不要回复11";
							$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $resultStr;
									
						 }//没有else
						 break;//case "event" 结束				
					}
                    
                    case "text"://接收到文字信息，关键词和非关键词的自动回复
                    {
                        //关键词设置		开始
                        if(!empty($keyword))
                        {
                            $msgType = "text";
                            
                            //天气	查询		开始
							$keywordsectionArray = explode(" ", $keyword);
                            if ($keywordsectionArray[1] == "天气")
                            {
                                $url = "http://api.map.baidu.com/telematics/v2/weather?location={$keywordsectionArray[0]}&ak=6eeplpjtjf18kHHAKj3ckm8z";
                                $fa = file_get_contents($url);
                                $fa = simplexml_load_string($fa);
                                $city = $fa -> currentCity;
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
                            
                                $d1 = "【".$city."今日"."天气】\n".$da1.$w1.$p1.$q1."\n";
                                $d2 = $da2.$w2.$p2.$q2."\n";
                                $d3 = $da3.$w3.$p3.$q3;
                                $contentStr = $d1.$d2.$d3;
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                            }
                            else if ($keywordsectionArray[0] == "翻译")
                            {
                                $tranurl = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=CA3vvUGWMCvweXxS4ax2ULo2&q={$keywordsectionArray[1]}&from=auto&to=auto";
                                $transtr = file_get_contents($tranurl);//读入文件
                                $transon = json_decode($transtr);//json解析
                                //print_r($transon);
                                $contentStr = $transon->trans_result[0]->dst;//读取内容
                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                            }
                            else if ($keywordsectionArray[0] == "足球")
                            {
                                include('simple_html_dom.php');
                                $html = file_get_html('http:/www.zhiboba.cn/');
                                foreach($html->find('div#score_1') as $e)//西甲
                                    $xistr = $e->plaintext;
                                foreach($html->find('div#score_2') as $e)//法甲
                                    $fastr = $e->plaintext;	
                                foreach($html->find('div#score_3') as $e)//意甲
                                    $yistr = $e->plaintext;
                                foreach($html->find('div#score_4') as $e)//德甲
                                    $destr = $e->plaintext;	
                                foreach($html->find('div#score_5') as $e)//英超
                                    $yingstr = $e->plaintext;		
                                foreach($html->find('div#score_6') as $e)//中超
                                    $zhongstr = $e->plaintext;		
                                switch($keywordsectionArray[1])
                                {
                                    case "西甲":
                                        $contentStr = $xistr;
                                        break;
                                    case "法甲":
                                        $contentStr = $fastr;
                                        break;	
                                    case "意甲":
                                        $contentStr = $yistr;
                                        break;		
                                    case "德甲":
                                        $contentStr = $destr;
                                        break;
                                    case "中超":
                                        $contentStr = $zhongstr;
                                        break;
                                    default:
                                    //$contentStr = "足球+空格+关键词，目前关键词只能是西甲，法甲，意甲，德甲，英超，中超的比分！么么哒%>_<%~"；
                                }
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;                                
                            }
                            //天气 查询 结束

                            switch($keyword)
                            { 
                                //查询 时间 开始
                                case "？":
                                case "?":
                                case "时间":
                                case "现在时间":
                                case "当前时间":
                                {
                                    $contentStr = date("Y-m-d-w H:i:s",time());
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;
									break;                                    
                                }
                                //查询 时间 结束
                                
                                case "11"://自助菜单
                                {
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>10</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[调皮的上升气流君自助菜单]]></Title>
														</item>
                                                        
														<item>
															<Title><![CDATA[1.本月动漫资讯TOP10]]></Title>
														</item>    

														<item>
															<Title><![CDATA[2.本月游戏资讯TOP10]]></Title> 
														</item>
														
														<item>
															<Title><![CDATA[3.本月精选壁纸]]></Title>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200085708&idx=1&sn=0939510799c320fc342dffcbf7a61ac4#rd]]></Url>
														</item>   
														
														<item>
															<Title><![CDATA[4.本周推荐音乐]]></Title> 
														</item>
														
														<item>
															<Title><![CDATA[44.推荐歌曲信息]]></Title> 
														</item> 
														
														<item>
															<Title><![CDATA[5.开心的游乐场╰(*´︶`*)╯♡]]></Title>
														</item>													
																												
														<item>
															<Title><![CDATA[6.进入ACGN——PUB微社区✪υ✪]]></Title>
															<Url><![CDATA[http://wsq.qq.com/reflow/230757916]]></Url>
														</item> 
														
														<item>
															<Title><![CDATA[7.关于调皮的上升气流君｡：ﾟ(｡ﾉω＼｡)ﾟ･｡]]></Title>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200087054&idx=1&sn=f83fdbdd187597b332576bfc89e9776d#rd]]></Url>
														</item> 														

														<item>
															<Title><![CDATA[----------回复数字打开----------]]></Title> 
														</item> 														
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;								
                                }
                                                                
                                case "1"://||"本月动漫资讯TOP10"
								case "本月动漫资讯TOP10":
								case "本月动漫资讯":
                                case "动漫资讯":
								{
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>10</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[「摇曳百合」第3季实为OVA？！剧场上映正在企划中！]]></Title> 
															<Description><![CDATA[]]></Description>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D6Yo2vIZMFPSibCdiapTcHbb2wYFyltO95ORKkkE0PWkiawDvsFcEmJDP1ntdpuGpyNj38b1VSoyddw/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200080034&idx=1&sn=659093f1152eedb3395bd07a13e7bdc8#rd]]></Url>
														</item>
                                                        
														<item>
															<Title><![CDATA[春番「请问您今天要来点兔子吗?」情报追加 4月10日首播]]></Title>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D1wWtaLLEKDEppyzqREibwBDppdiaU5DnMAwDhRicXNd4Rmic5uPbnSibFv2rGSXJMRSjcvibnXpkjKdhQ/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200075877&idx=2&sn=dbaafbbea3d3bd010ed347c1d966d168#rd]]></Url>
														</item>    

														<item>
															<Title><![CDATA[4月26日上映剧场版「天降之物Final」最新预告CM公开]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CDlbNicfG8siaHzKa2z7ibFW1A8FeWz95liavjNs2uez5h7ZeqFicaYnuq1MHId1oC9og3hibhyWX9diczg/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200081935&idx=1&sn=b891472e5ad4f2c21cd2fc09d3956921#rd]]></Url>
														</item>    
                                                        
														<item>
															<Title><![CDATA[LOLI的正确打开方式？原创TV动画「天体的方式」公开]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CDlbNicfG8siaHzKa2z7ibFW1w8gaSPuTEbrF5yJx0VqDRdUxTt6iaO2E4qJxkMksKlRyE9wPzaMdKKg/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200081935&idx=2&sn=5de6eb152873dfd7a562d58a34bfd552#rd]]></Url>
														</item>   
                                                        
														<item>
															<Title><![CDATA[4月剑灵动画「Blade&Soul」第2弹预告PV公开 声优追加]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D6Yo2vIZMFPSibCdiapTcHbblAGibA00N1fwcDWKXbXukiaY50ic0ibibiaBA4TMnqxPFu8oP1J9sQz6Tb7A/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200080677&idx=3&sn=ee7ca7c4f6d138c902dbd299a4c1d49b#rd]]></Url>
														</item>                                                           
                                                        
														<item>
															<Title><![CDATA[双眼已亮瞎！那些被三次元毁掉的动漫]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7BicsZskNUQW5rrko2CP2cgPS1wYKwLvKS0Hr3eakWIlI2xv6zR2pW1HWibuzm3tOaw8Ta1YRia2c2JA/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200056712&itemidx=3&sign=0a5676942027cbaa9b64e4ba3cbad608#wechat_redirect]]></Url>
														</item>

														<item>
															<Title><![CDATA[【浮力_(:з」∠)_】寿屋「天降之物」伊卡洛斯&WAVE泳装缠流子彩色原型公开]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D6Yo2vIZMFPSibCdiapTcHbbcO63ZTnOibnwJuokMcESUOFuE7icPUJqW2dslpnkXTCQzBHe6MUMbJIg/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200080034&idx=3&sn=8f5d476aadd3f16f5fdf239743863cbc#rd]]></Url>
														</item> 

														<item>
															<Title><![CDATA[万名阿宅评出想成为恋人的动漫角色 银时、亚丝娜第一]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D1wWtaLLEKDEppyzqREibwBcsKEY7g5WsbSh5oX39oDWQsgJ6JQ9kfP384gmc0GNxGHrNq0HpAYZw/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200076354&idx=5&sn=489795b28d500e129a276559bfdd24bb#rd]]></Url>
														</item> 

														<item>
															<Title><![CDATA[春季特番「GJ部@」公开视觉图 动画BD将于5月14日发售]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7D1wWtaLLEKDEppyzqREibwB603wdRWJAXrIXCvfm8Z36EYAG11fqQ7RhDDewn8d7sAg0vRGtwn2gg/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200076354&idx=4&sn=b829d0c0d14fe7fb450bd5f8d28dab66#rd]]></Url>
														</item> 

														<item>
															<Title><![CDATA[【卧槽槽槽_(:з」∠)_】静香被和谐？剧场版「哆啦A梦」里出现迷之圣光！？]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7DS5hWw9JcSAFq1RLgiccFH3d36BZ4G0XdmsdEY8w8duyqicaO6GuWejISYtmbmyGHRgUvZHU1RJatQ/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200069729&idx=3&sn=1bb4e9c425401c9a66b88d8d6969661c#rd]]></Url>
														</item>                                                         
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;
								}
                                
                                case "2"://||"本月游戏资讯TOP10"
								case "本月游戏资讯TOP10":
								case "本月游戏资讯":
                                case "游戏资讯":
								{
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>10</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[“秒杀鬼泣”？神谷英树转投PS4平台 下周即将公布次世代新作品]]></Title> 
															<Description><![CDATA[]]></Description>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7BPiceERZ9mcuVoQe6tF9mJAzqjiciasibaS0csuBGtHDaRVibNfrcw59cdVjoDt3HQvaICI7zibibKcJQicw/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200058648&itemidx=6&sign=cf611f89995ef6ab26ea80b636a24ad0#wechat_redirect]]></Url>
														</item>
                                                        
														<item>
															<Title><![CDATA[国产游戏能做些什么？《最终幻想10》多重意义]]></Title>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7BPiceERZ9mcuVoQe6tF9mJAjb65CYGQvUwlllAQZOD47rR66eic3LOlC2gf5U0YtZmAIzQpYgJyJ8w/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200056712&itemidx=1&sign=a5f3d682240ef0b0e363f3e414700791#wechat_redirect]]></Url>
														</item>    

														<item>
															<Title><![CDATA[大锤开始招聘《使命召唤11》VFX技术开发人员 特效或将大幅提升 并开始发送测试邀请]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7BPiceERZ9mcuVoQe6tF9mJACteCg0vrOyzo1RJsmDKZkROqSts74V9cAVFx93avZe1bupicHhFibcGw/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200058648&itemidx=7&sign=f22da1190b41ea73708523c584d57ab1#wechat_redirect]]></Url>
														</item>    
                                                        
														<item>
															<Title><![CDATA[《使命召唤11》消息泄露 代号Black Smith]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7DSrrk2ZwVnagRxKkiaFryRuibKyvDoOO6Z6yuzurqYBlJmRCGwBWPfJMia748fEbNkhvn5H9x9Tnz5w/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200051891&itemidx=7&sign=a253974053cd2b11c2d913b142dd092b#wechat_redirect]]></Url>
														</item>   
                                                        
														<item>
															<Title><![CDATA[《最终幻想14：重生之境》国服4月首测 采用夜光引擎4K分辨率]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7DSrrk2ZwVnagRxKkiaFryRuoUG5ZG3mubu6RSrDr9aU8dy3ic7WqW2xK0nVZLnXmP7cNxxpTj6FYPA/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200051891&itemidx=2&sign=f9ed77c3ac09a43d4a64ced9994ad929#wechat_redirect]]></Url>
														</item>                                                           
                                                        
														<item>
															<Title><![CDATA[别小看花花草草！《植物大战僵尸》入侵EA大作]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7DC5G44lyWWavXwl0eYmtqgPjZoMvcIjOVdicPI5VSP91uwzlpFMUe7ZeDTibDoAMYAkrct5SmQFjbw/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDczOTA4NQ==&appmsgid=200048573&itemidx=1&sign=128af4adfb93e55cb18e6ca5cc11ac5d#wechat_redirect]]></Url>
														</item>                                                             
														
														<item>
															<Title><![CDATA[次世代新作《荣誉勋章2015(Medal of Honor 2015)》曝光！今年E3亮相]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CXRtQ06dFiafk7O58sib5PPpt3mLYiajvc8ThSvBmoZ8EBUZa8SAWTtIZyg5IEN556OIZHamMhHOVYQ/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200085045&idx=1&sn=eb56b44b3d3449cdb22eb08be52c0d2a#rd]]></Url>
														</item>    

														<item>
															<Title><![CDATA[《真三国无双7：猛将传》PC版确认 发售日与配置公布]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CXRtQ06dFiafk7O58sib5PPpBNHZp5cfUxCJrelb6b3tPibCV6Lwiaja8OlZgLo8eDvm3q4c4HDSZGPQ/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200085045&idx=5&sn=07d054765e18556ef277dbdc855520e6#rd]]></Url>
														</item>    
														
														<item>
															<Title><![CDATA[PC版画面有多好？《泰坦陨落》画质视频对比：PC vs Xbox One]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CDlbNicfG8siaHzKa2z7ibFW1buZCkrN3e9v6GFzQv0JgN2tAsv3TLiaxjibyDuWTcjryUAEDXMOeia44w/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200080677&idx=1&sn=f4af518da55a7614f1c3b31f7cf46c61#rd]]></Url>
														</item>    

														<item>
															<Title><![CDATA[能不能不要这么可爱！PC小游戏《点心大作战》]]></Title> 
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CDlbNicfG8siaHzKa2z7ibFW155vS77Lic6dia78sicsst0bQcHZXuqga87UhdsPxooTfsOWibIqapL4b4g/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200080677&idx=4&sn=18902b8be63df19ee570afed68ab1391#rd]]></Url>
														</item>    														
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;
								}
								
                                case "3"://"本月推荐动漫壁纸
                                case "动漫壁纸":                      
                                case "壁纸":
								{
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>1</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[【动漫+游戏壁纸精选】三月的DarkBlue]]></Title> 
															<Description><![CDATA[【摘要】风儿柔柔，花儿遍地，蝶儿满天。包含轻音，火影，黑岩射手，东方，使命召唤，剑灵，鬼泣等壁纸。]]></Description>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7CXRtQ06dFiafk7O58sib5PPp3XlYkOGHP8lJsaV1PFL6QvolE93WkzsN9hWJ7pMiaicOXqYDic2q4JFWA/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200085708&idx=1&sn=0939510799c320fc342dffcbf7a61ac4#rd]]></Url>
														</item>                                                         
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;
								}
                                
                                case "4"://||"游戏音乐"||"音乐":
                                case "游戏音乐":                      
                                case "音乐":
                                case "听音乐":
                                {
                                    $msgType = "music";                        
                                    $musicTpl = "<xml>
                                                     <ToUserName><![CDATA[%s]]></ToUserName>
                                                     <FromUserName><![CDATA[%s]]></FromUserName>
                                                     <CreateTime>%s</CreateTime>
                                                     <MsgType><![CDATA[%s]]></MsgType>
														 <Music>
														 <Title><![CDATA[镜音リン メランコリック]]></Title>
														 <Description><![CDATA[I‘ve]]></Description>
														 <MusicUrl><![CDATA[http://sc.111ttt.com/up/mp3/158110/9434F6ED0B58F8105885CB5901865446.mp3]]></MusicUrl>
														 <HQMusicUrl><![CDATA[http://sc.111ttt.com/up/mp3/158110/9434F6ED0B58F8105885CB5901865446.mp3]]></HQMusicUrl>
                                                     </Music>
                                                 </xml>";
                                    $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType);
                                    echo $resultStr;
									
									$msgType = "text";
									$contentStr = "回复44：了解歌曲背景信息。";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;
                                    break;
                                }
								case "44":
								{
									$msgType = "text";
                                    $contentStr = "《メランコリック》[飞吻]\n==================\n木有提供。miss u~\n==================\n[愉快]回复4：收听推荐音乐\n==================\n[玫瑰]回复11：自助导航菜单";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr; 
									break;
								}
								
                                case "5"://开心的游乐场
                                {
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>10</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[调皮的气流君の游乐场\(≧▽≦)/]]></Title>
														</item>
                                                        
														<item>
															<Title><![CDATA[1.疯狂猜图]]></Title>
															<Url><![CDATA[http://pictoword.hortorgame.com/]]></Url>
														</item>    

														<item>
															<Title><![CDATA[2.2048]]></Title>
															<Url><![CDATA[http://gabrielecirulli.github.io/2048/?from=timeline&isappinstalled=0]]></Url>															
														</item>												
														
														<item>
															<Title><![CDATA[3.Flappy Bird]]></Title>
															<Url><![CDATA[http://www.flappybirdonweb.com/]]></Url>
														</item>
														
														<item>
															<Title><![CDATA[4.消除泡泡]]></Title>
															<Url><![CDATA[http://xwuz.com/bubble/]]></Url>
														</item>

														<item>
															<Title><![CDATA[5.六边拼图Entanglement]]></Title>
															<Url><![CDATA[http://entanglement.gopherwoodstudios.com/]]></Url>
														</item>														
														
														<item>
															<Title><![CDATA[------------游戏攻略------------]]></Title> 
														</item> 	
														
														<item>
															<Title><![CDATA[1.2048游戏攻略]]></Title>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200089646&idx=1&sn=4ab6d2204d7f5fbc0aff6260f4e49fe4#rd]]></Url>															
														</item>		
														
														<item>
															<Title><![CDATA[2.Flappy Bird游戏攻略]]></Title>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200089714&idx=1&sn=be850e3d4ad0427853db4504b85484f8#rd]]></Url>															
														</item>		
														
														<item>
															<Title><![CDATA[------------点击打开------------]]></Title> 
														</item> 															
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;								
                                }
								
                                case "6"://||"意见反馈"||"提意见"||"提建议"||"意见"||"建议"||"反馈":
                                case "意见反馈":
                                case "提意见":
                                case "提建议":
                                case "意见":
                                case "建议":
                                case "反馈":
                                {
									$msgType = "text";
                                    $contentStr = '泥有什么想法对窝！\n(=￣ω￣=)，人家是乖孩子的说╭(╯^╰)╮。不过为了能更好的为泥提供服务，窝会好心de勉强de考虑泥提的意见的\n(oﾟωﾟo)n/。说完意见后，记得对我说蟹蟹！[害羞]<a href = "http://1.darkblueacgstandby.sinaapp.com/advice.php">点这里</a>';
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                          
                                    break;
                                }
                                
                                case "7"://"关于调皮的上升气流君"
                                {
                                    //$contentStr = "关于调皮的上升气流君";
                                    //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    //echo $resultStr;                        
                                    //break;
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>1</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[调皮的上升气流君自助菜单]]></Title>
															<Description><![CDATA[【查看新版如何使用】DarkBlue——ACG平台第一阶段开发基本结束。大家尽情对窝回复，查看开发效果以及纠错。蟹蟹泥萌的关注和对调皮的上升气流君的支持。_(:з」∠)_]]></Description>
															<PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz/JiaD3ALapU7DEVic9bAhSSWib4MCYWLQQdglWxUGRh100xib9pmiaTuT6bzfO6hyQCxyZFnIyBcibeb4YcheMSFFN91A/0]]></PicUrl>
															<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NDczOTA4NQ==&mid=200087054&idx=1&sn=f83fdbdd187597b332576bfc89e9776d#rd]]></Url>
														</item>
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                    echo $resultStr;                          
                                    break;									
                                }
                                
                                case "大圆脸":
                                case "小圆脸":
                                case "圆神":
                                case "袁神":
                                case "袁帅":
                                {                                
                                	$contentStr = "就是个渣渣！";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                } 
                                
                                case "查询":
                                case "cx":
                                {
                                    $contentStr = "查询天气，词典，火车票，机票，外币汇率，快递，酒店，股票，电影，电话，医生，租房，菜谱，公交，养老保险，住房公积金，彩票，塔罗占卜，在线游戏。请回复关键字，如“词典”，“笑话”，“天气”等。一口气说这么多累死窝惹(╯﹏╰）";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                } 
									
                                case "词典":
                                case "fy":
                                {
                                    $contentStr = "试试翻译功能，方法：翻译+空格+要翻译的内容，例如“翻译 today”,“翻译 今天”，记得中间加空格。支持汉译英（包括长句），英译汉，日译汉（包括长句）英语学习好帮手哦~么么哒~";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }
                                
                                case "游戏":
                                case "在线游戏":
                                case "yx":
                                {
                                    $contentStr = '<a href="http://www.duopao.com/games/index">点击玩儿在线游戏</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                  
                                case "外币汇率":
                                case "hl":
                                {
                                    $contentStr = '<a href="http://dp.sina.cn/dpool/tools/forex/index.php?vt=4&pos=63">点击查询汇率</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                 
                                
                                case "机票":
                                case "jp":
                                {
                                    $contentStr = '<a href="http://touch.qunar.com/h5/flight/?bd_source=sina&pos=63&vt=4&clicktime=1392794657227&userid=user13927946572271773069619666785">点击查询/订购机票</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                } 
                                
                                case "酒店":
                                case "jd":
                                {
                                	$contentStr = '<a href="http://touch.qunar.com/h5/hotel/?bd_source=sina&pos=63&vt=4&clicktime=1392794740436&userid=user13927947404366652691895142198">点击查询酒店</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                } 
                                
                                case "快递":
                                {
                                	$contentStr = '<a href="http://m.kuaidi100.com/#input">点击查询快递</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                } 
                                
                                case "周边":
                                case "路线":
                                case "地图":
                                {
                                    $contentStr = '<a href="http://map.baidu.com/mobile/webapp/index/index/qt=cur&wd=西安市&from=maponline&tn=m01&ie=utf-8=utf-8/tab=line/?fromhash=1">点击查询路线/地图</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                 
                                
                                case "电影":
                                {
                                	$contentStr = '<a href="http://dp.sina.cn/dpool/tools/movie/?pos=63&vt=4">点击查询最新电影</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                
                                
                                case "电话":
                                {
                                	$contentStr = '<a href="http://m.46644.com/tool/tel/">点击查询生活常用电话</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }
                                
                                case "养老保险":
                                case "养老":
                                case "养老保险计算":
                                {
                                	$contentStr = '<a href="http://dp.sina.cn/dpool/tools/money/single.php?flag=old_per&pos=63&vt=4">点击计算养老保险</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }                                  
                                
                                case "占卜":
                                case "塔罗":
                                case "塔罗占卜":
                                case "塔罗牌":
                                {
                                	$contentStr = '<a href="http://ast.sina.cn/?sa=t282d771v166&pos=19&vt=4">点击占卜</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }  
                                
                                case "彩票":
                                case "查彩票":
                                case "查询彩票":
                                case "彩票查询":
                                case "双色球":
                                case "七星彩":
                                case "大乐透":
                                case "福彩":
                                case "足彩":
                                {
                                	$contentStr = '<a href="http://loto.sina.cn/i/open.do?label=1&sid=fc055b3a-d72c-41bf-96bc-b8e436ea79ea&agentId=233258&vt=3">点击查彩票</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }                                 
                                
                                case "公积金":
                                case "查公积金":
                                case "查住房公积金":
                                case "住房公积金":
                                case "查询公积金":
                                case "公积金查询":
                                case "查询住房公积金":
                                case "住房公积金查询":
                                {
                                	$contentStr = '<a href="http://dp.sina.cn/dpool/tools/money/single.php?flag=house_per&pos=63&vt=4">点击查住房公积金</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                } 
                                
                                case "租房":
                                {
                                	$contentStr = '<a href="http://m.soufun.com/zf/xian/?sf_source=ucbrowser04">点击查租房</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }                                
                                
                                case "查火车票":
                                case "查火车":
                                case "火车":
                                case "查询火车票":
                                case "查询火车":
                                case "火车查询":
                                case "火车票":
                                case "火车余票":
                                {
                                	$contentStr = '<a href="http://wap.tieyou.com/sina/index_yupiao.html?pos=63&vt=4">点击查询火车</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }
                                
                                case "医生":
                                case "大夫":
                                case "感冒":
                                case "看病":
                                case "不舒服":
                                case "难受":
                                {
                                	$contentStr = '<a href="http://m.haodf.com/touch">点击进入在线医生</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }
                                
                                case "查菜谱":
                                case "厨房":
                                case "做饭":
                                case "菜谱":
                                case "查询菜谱":
                                case "菜谱查询":
                                {
                                	$contentStr = '<a href="http://m.xiachufang.com/">点击查询菜谱</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;   
                                }
                                
                                case "查公交":
                                case "公交查询":
                                case "查询公交":
                                case "公交":
                                {
                                	$contentStr = '<a href="http://dp.sina.cn/dpool/tools/bus/index2.php">点击查询公交</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;
                                }
                                
                                case "查询天气":
                                case "天气查询":
                                case "查天气":
                                case "天气":
                                {
                                	$contentStr = "本来不想告诉你，看你这么诚心，还是好心告诉你吧~O(∩_∩)O哈哈~[愉快]。查天气请回复，格式：地名+空格+天气，如'北京 天气','上海 天气'。";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;
                                }

                                
                                case "鬼故事":
                                {
                                    //<!-- 数据库 开始 -->
                                            // 连主库
                                            $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                                
                                            // 连从库
                                            // $link = mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                                
                                            if($link)
                                            {
                                                mysql_select_db(SAE_MYSQL_DB, $link);
                                                //开始执行
                                                //echo "link successfully!";
                                                $random_id = rand(1,20);
                                                $sql = "SELECT ghost_stories_content FROM ghost_stories WHERE ghost_stories_id='$random_id'";
                                                $result = mysql_query($sql);//执行sql语句
                                                $row = mysql_fetch_row($result);  
                                                $contentStr = $row[0];
                                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                                echo $resultStr;                                    
                                                //          break;                                                
                                             }
                                    //<!-- 数据库 结束 -->                                     
                                    //$contentStr = "更多功能，请回复“查询”么么哒O(∩_∩)O~~。";
                                    //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    //echo $resultStr;                                    
                                    break;                                    
                                }                                
                                
                                case "笑话":
                                case "冷笑话":
                                {
                                    //<!-- 数据库 开始 -->
                                            // 连主库
                                            $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                                
                                            // 连从库
                                            // $link = mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                                
                                            if($link)
                                            {
                                                mysql_select_db(SAE_MYSQL_DB, $link);
                                                //开始执行
                                                //echo "link successfully!";
                                                $random_id = rand(1,100);
                                                $sql = "SELECT jokes_content FROM jokes WHERE jokes_id='$random_id'";
                                                $result = mysql_query($sql);//执行sql语句
                                                $row = mysql_fetch_row($result);  
                                                $contentStr = $row[0];
                                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                                echo $resultStr;                                    
                                                //          break;                                                
                                             }
                                    //<!-- 数据库 结束 -->                                     
                                    //$contentStr = "更多功能，请回复“查询”么么哒O(∩_∩)O~~。";
                                    //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    //echo $resultStr;                                    
                                    break;                                    
                                }
                                
								
                                default://非关键字 自动回复
                                {
                                    $order = rand(1, 33);
                                    switch($order)
                                    {
                                        case "1":
                                            $contentStr = "哇咔咔[玫瑰]，不如给我发图片试试(≧v≦)o~~！";
                                            break;
                                        case "2":
                                            $contentStr = "哦哦阿拉拉拉[愉快]，查天气请回复，地名+空格+天气，如'北京 天气','上海 天气'。";
                                            break;
                                        case "3":
                                        	$contentStr = "如果泥回复问号，试试吧_(:з」∠)_~";
                                        	break;
                                        case "4":
                                        	$contentStr = "泥在哪儿，给我发送你的位置(地图的地理位置)吧，窝好想泥的说[可怜]，看看泥和窝距离多远~";
                                        	break;
                                        case "5":
                                        	$contentStr = "如果窝喜欢泥[飞吻]，泥也会喜欢窝吗[可怜]~";
                                        	break;
                                        case "6":
                                        	$contentStr = "如果泥想窝了[可怜]，就陪陪我，和我说说话吧[拥抱]~想知道怎么查天气吗~试试回复查天气这三个字~[太阳]";
                                        	break;
                                        case "7":
                                        	$contentStr = "喵~~-...-(明明是狗，却要发出鸭子的叫声)_(:з」∠)_，没错，这就对了[愉快]";
                                        	break;
                                        case "8":
                                        	$contentStr = "如果有疑问请发消息，上升的气流君会尽可能在24小时内给你回复[拥抱]。和窝聊天窝会告诉泥很多小秘密哦~[害羞]";
                                        	break;
                                        case "9":
                                        	$contentStr = "“呐，你知道吗？听说樱花飘落的速度是秒速五厘米哦.秒速5厘米 那是樱花飘落的速度 那么怎样的速度 才能走完我与你之间的距离？”——《秒速五厘米》";
                                        	break;
                                        case "10":
                                        	$contentStr = "虚伪的眼泪，会伤害别人。虚伪的笑容，会伤害自己。——《叛逆的鲁鲁修》";
                                        	break;
                                        case "11":
                                        	$contentStr = "“我在未来等你” “嗯，我马上去，我会用跑的！——《穿越时空的少女》";
                                        	break;
                                        case "12":
                                        	$contentStr = "知道雪为什么是白色的吗？因为它忘记了自己曾经的颜色。那年你站在樱花下冲着我淡淡的微笑，还记得曾经纯真的你吗？";
                                        	break;
                                        case "13":
                                        	$contentStr = "故事成败赌结尾上失败也种趣事。——自来也";
                                        	break;
                                        case "14":
                                        	$contentStr = "“人总是被一定的认知所束缚而活着，这就是现实。而这种认知本身又是暧昧不清的东西，现实也许只是镜花水月。”——宇智波·鼬";
                                        	break;
                                        case "15":
                                        	$contentStr = "“时间能冲淡痛苦，但我并不指望时间的慰疗。”——摘自《火影忍者》";
                                        	break;
                                        case "16":
                                        	$contentStr = "真相只有一个，那就是——涩郎就是泥！猜猜这是出自哪部动漫的[坏笑]~";
                                        	break;
                                        case "17":
                                        	$contentStr = "我要代表月亮惩罚泥！猜猜这是出自哪部动漫的，哇咔咔~";
                                        	break;
                                        case "18":
                                        	$contentStr = "赐予我力量吧我是调皮的上升气流君[拳头]~";
                                        	break;
                                        case "19":
                                        	$contentStr = "真相只有一个，那就是——涩郎就是泥！猜猜这是出自哪部动漫的[坏笑]~";
                                        	break;
                                        case "20":
                                        	$contentStr = "如果你喜欢我就点击右上角，→调皮的上升气流君的名片 → 右上角放到桌面上~我也会喜欢你的~[可怜]，又想泥了[大哭]~";
                                        	break;
                                        case "21":
                                        	$contentStr = "绝对不要动，屏住呼吸，与大自然融为一体，你就成了宇宙的一部分，宇宙也成了你的一部分。";
                                        	break;
                                        case "22":
                                        	$contentStr = "问おう、贵方が私のマースタか";
                                        	break;
                                        case "23":
                                        	$contentStr = "明明感觉距离很近，但伸手却又抓不到。即使这样， 即使望尘莫及，亦有留在心中的东西，曾身处同一时间层，曾仰望过同一样东西，只要记着这些，就算相互远离，也依然可以相信我们还是同在。现在要不停奔跑，只要目标远大，总有一天，会赶上那目标……";
                                            break;
                                        case "24":
                                        	$contentStr = "把剑扔了，做我的妻子吧~";
                                        	break;
                                        case "25":
                                        	$contentStr = "“……原来如此，军令状吗?那么就是说只要我把敌人的首级给你带来，你便会给我买裤子。是这样吗?”——Fate/Zero·Rider";
                                        	break;
                                        case "26":
                                        	$contentStr = "忠道，乃大义所在。不要给他的努力蒙羞。——Fate/Zero·Rider";
                                        	break;
                                        case "27":
                                        	$contentStr = "「我已经没有令咒了！不当Master了！为什么还要带我一起去？我——」「不管你是不是Master，你是我的朋友，这点不会改变。」「……我……我这样的人……真……真的可以……在你身边吗……」　　「与我共赴战场那么多次，现在还说这种话干什么。你这笨蛋。」　　「你不是与我共同面对敌人的男子汉吗？那么，你就是朋友。挺起胸膛和我比肩而立吧。」「……」韦伯忘了自嘲。忘了今天以前的屈辱、对明日的胆怯以及面对死亡那一瞬间的恐惧。——Fate/Zero";
                                        	break;
                                        case "28":
                                        	$contentStr = "赌上这把剑的荣耀，我会守护你到最后。——Fate/Zero·Saber";
                                        	break;
                                        case "29":
                                        	$contentStr = "越是如此渺小，却越要凭借这个渺小的身体凌驾于整个世界之上。这才是最令人激动的感觉。听！这才是我征服王心脏的鼓动！——Fate/Zero·Rider";
                                        	break;
                                        case "30":
                                        	$contentStr = "“展示梦之所在是为王的任务。而见证梦的终焉，并将它永传后世则是你为臣的任务。活下去，韦伯。见证这一切，把为王的生存方式，把伊斯坎达尔飞驰的英姿传下去。”——Fate/Zero·Rider";
                                        	break;
                                        case "31":
                                        	$contentStr = "“呐，小切，你想成为什么样的大人？” 在令人目炫的阳光下，她问。 她的温柔，她的微笑，他决对不会忘记。 世界如此美丽，好希望，时间永远停留在这个幸福的瞬间。 不由自主地，他说出了心中的誓言。 今天的心情，我永远也不会忘记。 “我啊，想成为正义的化身哦。”——Fate Stay Night/Zero";
                                        	break;
                                        default:
                                            $contentStr = "告诉你个咪咪~回复11有惊喜哦~O(∩_∩)O哈哈~如果你知道了，不妨和窝俩聊天，我会告诉你别的小秘密哦~[发抖]";
                                    }
                                    
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                              
                                }
                            }
                        }
                        //关键词设置	结束
                        else
						{
                            echo "Input something...";
						}
						break;//case "text" 结束						
                    }
					//以下自定义回复内容，似乎只有高级接口权限的才能开启，目前先写上
					case "image":
					{
                        $type = $postObj -> MsgType;
                        $textTpl = "<xml>
                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                        <CreateTime>%s</CreateTime>
                                        <MsgType><![CDATA[text]]></MsgType>
                                        <Content><![CDATA[%s]]></Content>
                                        <FuncFlag>0</FuncFlag>
                                    </xml>";         
                        $order = rand(1, 5);
                        switch($order)
                        {
                            case "1":
                            	$contentStr = "么么哒[玫瑰]，泥发的图片o(≧v≦)o~~好棒~！";
                            	break;
                            case "2":
                            	$contentStr = "泥肿么又给窝发黄图，还能愉快的玩耍不[坏笑]~";
                            	break;
                            case "3":
                            	$contentStr = "能发个泥的照片么[害羞]，泥发窝就发，么么[亲亲]~";
                            	break;
                            case "4":
                            	$contentStr = "这是你去过的地方吗？这里是哪儿[晕]~";
                            	break;
                            default:
                            	$contentStr = "这是个撒么[发呆]，泥发的图片好无聊[怄火]";
                        }
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
						echo $resultStr;  
						break;//case "image" 结束
					}
					
					case "link":
					{	
						$contentStr = "泥发的链接有病毒吧←。←";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
						echo $resultStr;  
						break;//case "link" 结束
					}
					
					case "location":
					{		
                        $type = $postObj -> MsgType;
                        $latitude = $postObj -> Location_X;//地理位置X
                        $longitude = $postObj -> Location_Y;//地理位置Y		                        
                        $textTpl = "<xml>
                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                        <CreateTime>%s</CreateTime>
                                        <MsgType><![CDATA[text]]></MsgType>
                                        <Content><![CDATA[%s]]></Content>
                                        <FuncFlag>0</FuncFlag>
                                    </xml>";
                        $url = "http://api.map.baidu.com/telematics/v3/distance?waypoints=34.1593625,108.9074895;{$latitude},{$longitude}&ak=6eeplpjtjf18kHHAKj3ckm8z";
                        $fa = file_get_contents($url);//108.907489,34.159362
                        $f = simplexml_load_string($fa);
                        $juli = $f -> results -> distance;
                        $juli = round($juli, 2);
                        $contentStr = "泥的纬度是{$latitude},经度是{$longitude}，泥和窝距离{$juli}米远,泥已被窝锁定\n<(￣ c￣)y▂ξ，快到碗里来[饭]。miss u\n==================\n回复11：打开自助菜单[飞吻]";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
						echo $resultStr;  
						break;//case "location" 结束
					}
					
					default:
					{
						$contentStr = "[哈欠]此项功能尚未开发！[敲打]\n不如回复6，到意见去给窝提意见[拥抱]\n(＃￣▽￣＃)";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;  						
					}
				}//switch($type) 结束
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