<?php
    // ����1.����appid��appsecret
    $appid = 'wxff4f410748ba11027';  //�˴���д�󶨵�΢�Ź��ںŵ�appid
    $appsecret = '246fbf3e581efeff3b11100aa17d4c4'; //�˴���д�󶨵�΢�Ź��ںŵ���Կid

    // ����2.����ǩ���������
    function nonceStr($length){
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJK1NGJBQRSTUVWXYZ';//�漴����62���ַ�
        $strlen = 62;
        while($length > $strlen){
        $str .= $str;
        $strlen += 62;
        }
        $str = str_shuffle($str);
        return substr($str,0,$length);
    }

    // ����3.��ȡaccess_token
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

    // ����4.��ȡticket
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";
    $res = json_decode ( http_get ( $url ) );
    $ticket = $res->ticket;


    // ����5.����wx.config��Ҫ�Ĳ���
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
    <title>΢�ŷ���ӿ�ʾ��</title>
	<meta name="keywords" content="΢�ŷ���ӿ�ʾ��" />   
    <meta name="description" content="����һ��΢�ŷ���ӿ�ʾ��Ԥ��ҳ�棬���Է��͵�΢�Ŷ�Ԥ����ת�������ѻ��߷�������Ȧ����Ч����Σ�" />
</head>
<body>
<p></p>
<h1 style="text-algin:center;">����һ��΢�ŷ���ӿ�ʾ��Ԥ��ҳ�棬���Է��͵�΢�Ŷ�Ԥ����ת�������ѻ��߷�������Ȧ����Ч����Σ�</h1>
<!--����6.����JS�ӿ�-->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
        debug: true, // ��������ģʽ,���õ�����api�ķ���ֵ���ڿͻ���alert��������Ҫ�鿴����Ĳ�����������pc�˴򿪣�������Ϣ��ͨ��log���������pc��ʱ�Ż��ӡ��
        appId: 'wxff4f410721541a1967', // ������ںŵ�Ψһ��ʶ
        timestamp: "<?php echo $ws["timestamp"]; ?>", // �������ǩ����ʱ���
        nonceStr: '<?php echo $ws["nonceStr"]; ?>', // �������ǩ���������
        signature: '<?php echo $ws["signature"]; ?>',// ���ǩ��������¼1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo'
        ] // �����Ҫʹ�õ�JS�ӿ��б�����JS�ӿ��б����¼2
    });
  var wstitle = "΢�ŷ���ӿ�ʾ��Ԥ��";  //�˴���д�������
  var wsdesc = "����һ��΢�ŷ���ӿ�ʾ��Ԥ��ҳ�棬���Է��͵�΢�Ŷ�Ԥ����ת�������ѻ��߷�������Ȧ����Ч����Σ�"; //�˴���д������
  var wslink = "<?php echo $surl; ?>"; //�˴���ȡ��������
  var wsimg = "http://www.yudouyudou.com/demo/wxshare/wx.jpg"; //�˴���ȡ��������ͼ

</script>
<script src="http://www.yudouyudou.com/demo/wxshare/wxshare.js"></script>
</body>
</html>