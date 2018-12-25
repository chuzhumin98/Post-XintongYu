<?php
    // 步骤1.设置appid和appsecret
    $appid = 'wxff4f410748ba11027';  //此处填写绑定的微信公众号的appid
    $appsecret = '246fbf3e581efeff3b11100aa17d4c4'; //此处填写绑定的微信公众号的密钥id

    // 步骤2.生成签名的随机串
    function nonceStr($length){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJK1NGJBQRSTUVWXYZ';//随即串，62个字符
        $strlen = 62;
        while($length > $strlen){
        $str .= $str;
        $strlen += 62;
        }
        $str = str_shuffle($str);
        return substr($str,0,$length);
    }

    // 步骤3.获取access_token
    $result = http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
    $json = json_decode($result,true);
    $access_token = $json['access_token'];

    function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    // 步骤4.获取ticket
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";
    $res = json_decode ( http_get ( $url ) );
    $ticket = $res->ticket;


    // 步骤5.生成wx.config需要的参数
    $surl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $ws = getWxConfig( $ticket,$surl,time(),nonceStr(16) );

    function getWxConfig($jsapiTicket,$url,$timestamp,$nonceStr) {
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1 ( $string );

        $WxConfig["appId"] = $appid;
        $WxConfig["nonceStr"] = $nonceStr;
        $WxConfig["timestamp"] = $timestamp;
        $WxConfig["url"] = $url;
        $WxConfig["signature"] = $signature;
        $WxConfig["rawString"] = $string;
        return $WxConfig;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="gbk">
    <title>微信分享接口示例</title>
	<meta name="keywords" content="微信分享接口示例" />   
    <meta name="description" content="这是一个微信分享接口示例预览页面，可以发送到微信端预览并转发给朋友或者分享朋友圈看看效果如何！" />
</head>
<body>
<p></p>
<h1 style="text-algin:center;">这是一个微信分享接口示例预览页面，可以发送到微信端预览并转发给朋友或者分享朋友圈看看效果如何！</h1>
<!--步骤6.调用JS接口-->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wxff4f410721541a1967', // 必填，公众号的唯一标识
        timestamp: "<?php echo $ws["timestamp"]; ?>", // 必填，生成签名的时间戳
        nonceStr: '<?php echo $ws["nonceStr"]; ?>', // 必填，生成签名的随机串
        signature: '<?php echo $ws["signature"]; ?>',// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
  var wstitle = "微信分享接口示例预览";  //此处填写分享标题
  var wsdesc = "这是一个微信分享接口示例预览页面，可以发送到微信端预览并转发给朋友或者分享朋友圈看看效果如何！"; //此处填写分享简介
  var wslink = "<?php echo $surl; ?>"; //此处获取分享链接
  var wsimg = "http://www.yudouyudou.com/demo/wxshare/wx.jpg"; //此处获取分享缩略图

</script>
<script src="http://www.yudouyudou.com/demo/wxshare/wxshare.js"></script>
</body>
</html>