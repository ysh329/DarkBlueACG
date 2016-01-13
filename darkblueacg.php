<meta charset=utf-8>
<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "darkblue");
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
                            else if ($keywordsectionArray[0] == "歌名")
                            {
                                $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                if($link)
                                {
                                    mysql_select_db(SAE_MYSQL_DB, $link);

                                    $sql = "SELECT title FROM jay_music WHERE lrc like '%$keywordsectionArray[1]%'";
                                    $result = mysql_query($sql);//执行sql语句
                                    $row = mysql_fetch_row($result);
                                    $contentStr = "我猜是《".$row[0]."》[害羞]，对吗~";
                                    if ($row[0] == "")
                                    {
                                        $contentStr = "没搜到这首歌的歌名，可能是给出的歌词片段太长。请再试试哈O(∩_∩)O~";
                                    }
                                    
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                }                                 
                            }
                            else if ($keywordsectionArray[0] == "听")
							{
								$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
								if($link)
								{
									mysql_select_db(SAE_MYSQL_DB, $link);
																		 
									$sql = "SELECT title FROM jay_music WHERE title='$keywordsectionArray[1]'";//根据keyword找出title
									$result = mysql_query($sql);//执行sql语句
									$row = mysql_fetch_row($result);
									$title = $row[0];
									
									if ($title == "")//随机情况下，即没有这首歌
									{
										$sql = "select count(*) FROM jay_music";//先随机出一个id
										$result = mysql_query($sql);
										$rand_id_max = mysql_fetch_row($result);
										$id = rand(1, $rand_id_max[0]);
										
										$sql = "select title FROM jay_music WHERE id=$id";
										$result = mysql_query($sql);
										$row = mysql_fetch_row($result);
										$title = $row[0];
										
										$sql = "select url FROM jay_music WHERE id=$id";
										$result = mysql_query($sql);
										$row = mysql_fetch_row($result);
										$url = $row[0];
										
										$sql = "select author FROM jay_music WHERE id=$id";
										$result = mysql_query($sql);
										$row = mysql_fetch_row($result);
										$author = $row[0];
									}
									else//有这首歌
									{
										$sql = "select author FROM jay_music WHERE title='$title'";//有title根据title，找出对应的author和url
										$result = mysql_query($sql);
										$row = mysql_fetch_row($result);
										$author = $row[0];
										
										$sql = "select url FROM jay_music WHERE title='$title'";//有title根据title，找出对应的author和url
										$result = mysql_query($sql);
										$row = mysql_fetch_row($result);
										$url = $row[0];
									}
								}                                         
								
                                $zero = "稻香";
								$msgType = "music";                        
								$musicTpl = "<xml>
												 <ToUserName><![CDATA[%s]]></ToUserName>
												 <FromUserName><![CDATA[%s]]></FromUserName>
												 <CreateTime>%s</CreateTime>
												 <MsgType><![CDATA[%s]]></MsgType>
													 <Music>
													 <Title><![CDATA[$title]]></Title>
													 <Description><![CDATA[$author]]></Description>
													 <MusicUrl><![CDATA[$url]]></MusicUrl>
													 <HQMusicUrl><![CDATA[$url]]></HQMusicUrl>
												 </Music>
											 </xml>";
								$resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType);
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
                                case "kd":
                                {
                                	$contentStr = '<a href="http://m.kuaidi100.com/#input">点击查询快递</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                } 
                                
                                case "周边":
                                case "路线":
                                case "地图":
                                case "dt":
                                {
                                    $contentStr = '<a href="http://map.baidu.com/mobile/webapp/index/index/qt=cur&wd=西安市&from=maponline&tn=m01&ie=utf-8=utf-8/tab=line/?fromhash=1">点击查询路线/地图</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                 
                                
                                case "电影":
                                case "dy":
                                {
                                	$contentStr = '<a href="http://dp.sina.cn/dpool/tools/movie/?pos=63&vt=4">点击查询最新电影</a>'."更多查询功能请回复关键字“查询”么么哒~❤";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;                                    
                                	break;  
                                }                                
                                
                                case "电话":
                                case "dh":
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
                                case "zb":
                                case "tl":
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
                                case "cp":
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
                                case "hc":
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
                                case "ys":
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
                                case "cp":
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
                                case "gj":
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
                                case "tq":
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
                                        
                                        $sql = "select count(*) from ghost_stories";
                                        $result = mysql_query($sql);
                                        $rand_id_max = mysql_fetch_row($result);
                                        
                                        $random_id = rand(1, $rand_id_max[0]); 
                                        
                                        $sql = "SELECT ghost_stories_content FROM ghost_stories WHERE ghost_stories_id='$random_id'";
                                        $result = mysql_query($sql);//执行sql语句
                                        $row = mysql_fetch_row($result);  
                                        $contentStr = "【一点都不吓人de鬼故事】\n".$row[0];
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                    
                                        //          break;                                                
                                    }                                  
                                    break;                                    
                                }                                
                                
                                case "笑话":
                                case "冷笑话":
                                {
                                    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                    if($link)
                                    {
                                        mysql_select_db(SAE_MYSQL_DB, $link);
                                        
                                        $sql = "select count(*) from jokes";
                                        $result = mysql_query($sql);
                                        $rand_id_max = mysql_fetch_row($result);
                                        
                                        $random_id = rand(1, $rand_id_max[0]); 
                                        
                                        $sql = "SELECT jokes_content FROM jokes WHERE jokes_id='$random_id'";
                                        $result = mysql_query($sql);//执行sql语句
                                        $row = mysql_fetch_row($result);  
                                        $contentStr = "【没有笑点de笑话】\n".$row[0];
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                    
                                    }                               
                                    break;                                    
                                }
                                
                                case "推理":
                                case "推理题":
                                {
                                    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                    if($link)
                                    {
                                        mysql_select_db(SAE_MYSQL_DB, $link);
                                        
                                        $sql = "select count(*) from inference";
                                        $result = mysql_query($sql);
                                        $rand_id_max = mysql_fetch_row($result);
                                        
                                        $random_id = rand(1, $rand_id_max[0]); 
                                        
                                        $sql = "SELECT inference_content FROM inference WHERE inference_id='$random_id'";
                                        $result = mysql_query($sql);//执行sql语句
                                        $row = mysql_fetch_row($result);  
                                        $contentStr = "【没有一点难度de推理】\n".$row[0]."仔细想一想，再看→".'<a href="http://wap.baidu.com/">答案</a>';
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                    
                                    }                               
                                    break;                                    
                                }

                                case "帅哥":
                                case "正太":
                                case "大叔":
                                case "蜀黍":
                                {
                                    $random_id = rand(1, 65); 
                                    $pic_url = "http://darkblueacgstandby-darkblue.stor.sinaapp.com/images/lady killer/".$random_id.".jpg";

                                    $urlasdf = "http://bizhi.zhuoku.com/2010/10/22/kuanping/kuanping39.jpg";
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>1</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[说好de帅哥_(:з」∠)_]]></Title> 
															<Description><![CDATA[【注意】喜欢点击，高清大图保存拿去当壁纸~哦哦哇咔咔~注意流量哦~❤]]></Description>
															<PicUrl><![CDATA[".$pic_url."]]></PicUrl>
                                                            <Url><![CDATA[".$pic_url."]]></Url>
														</item>                                                         
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $MsgType);
                                    echo $resultStr;                          
                                    break;                                    
                                }                                
                                
                                case "美女":
                                case "loli":
                                case "萝莉":
                                {
                                    $random_id = rand(1, 1011); 
                                    $pic_url = "http://darkblueacgstandby-darkblue.stor.sinaapp.com/images/loli/".$random_id.".jpg";
										
                                    $urlasdf = "http://bizhi.zhuoku.com/2010/10/22/kuanping/kuanping39.jpg";
                                    $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>1</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[说好de萝莉o(≧v≦)o~~]]></Title> 
															<Description><![CDATA[【注意】喜欢点击，高清大图保存拿去当壁纸~哦哦哇咔咔~注意流量哦~❤]]></Description>
															<PicUrl><![CDATA[".$pic_url."]]></PicUrl>
                                                            <Url><![CDATA[".$pic_url."]]></Url>
														</item>                                                         
													</Articles>
												</xml>";                               
                                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $MsgType);
                                    echo $resultStr;                          
                                    break;                                    
                                }
								
                                case "音乐":
                                case "歌曲":
                                case "听音乐":
                                {
                                    $msgType = "text";                        
                                    $contentStr = "想听音乐，那窝就大发慈悲的告诉泥技能使用方法(￣.￣)+。回复“听+空格+歌名”，如“听 稻香”，“听 东风破”。目前只支持周杰伦的歌曲_(:з」∠)_，若歌名不是周杰伦的歌曲，则随机返回一首歌曲。红红火火❤~";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;
                                    break;
                                }
                                
                                case "搜歌名":
                                {
                                    $msgType = "text";                        
                                    $contentStr = "知道歌词，但不知道歌名？∑( ° △ °|||)︴就请回复“歌名+空格+歌词片段”，如“歌名 蝉鸣的夏季”，“歌名 沙漠之中”。目前只支持周杰伦的歌曲_(:з」∠)_。么么哒❤~";
                                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                    echo $resultStr;
                                    break;                                    
                                }
                                
                                default://非关键字 自动回复
                                {
                                    $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                                    if($link)
                                    {
                                        mysql_select_db(SAE_MYSQL_DB, $link);
                                        
                                        $sql = "select count(*) from random_response";
                                        $result = mysql_query($sql);
                                        $rand_id_max = mysql_fetch_row($result);
                                        
                                        //$random_id = rand(1, $rand_id_max[0]); 
                                        $random_id = rand(1, 5); 
                                        
                                        $sql = "SELECT random_response_content FROM random_response WHERE random_response_id='$random_id'";
                                        //$sql = "SELECT random_response_content FROM random_response WHERE random_response_quiz like '%$keyword%'";
                                        //$sql = "SELECT random_response_content FROM random_response WHERE random_response_content like '%$keyword%'";
                                        $result = mysql_query($sql);//执行sql语句
                                        $row = mysql_fetch_row($result);
                                        $contentStr = $row[0];
                                        //if ($row[0] == "")
                                        //{
                                        //    $contentStr = "啥都没有[猪头]";
                                        //}
                                        
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                    
                                    }                         
                                }
                            }//switch($keyword)结束
                        }//if(!empty($keyword))	结束
                        else//keyword是空值，就是说用户啥都没输入
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
                        $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                        if($link)
                        {
                            mysql_select_db(SAE_MYSQL_DB, $link);
                           
                            $sql = "select count(*) from random_response_images";
                            $result = mysql_query($sql);
                            $row = mysql_fetch_row($result);
                            
                            $random_id = rand(1, $row[0]);
                            $sql = "SELECT random_response_images_content FROM random_response_images WHERE random_response_images_id='$random_id'";
                            $result = mysql_query($sql);//执行sql语句
                            $row = mysql_fetch_row($result);
                            $contentStr = $row[0];
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
                            echo $resultStr;   
                        }           
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
						$contentStr = "[哈欠]此项功能尚未开发！[敲打]\n不如回复6，到意见区给窝提意见[拥抱]\n(＃￣▽￣＃)";
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