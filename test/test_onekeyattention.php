<script language=
"javascript"
type=
"text/javascript"
>
function
WeiXinAddContact(wxid) {   
 
if
(typeof WeixinJSBridge ==
'undefined'
)
return
false;         
WeixinJSBridge.invoke(
'addContact'
, {            

webtype:
'1'
,            
username: wxid        
},
 
function
(d) {            
// 返回d.err_msg取值，d还有一个属性是err_desc             // add_contact:cancel 用户取消             // add_contact:fail　关注失败             // add_contact:ok 关注成功             // add_contact:added 已经关注            // WeixinJSBridge.log(d.err_msg);                    
});
}
</script>
<a href="#" onclick="WeiXinAddContact('gh_935233552e61')">关注</a>