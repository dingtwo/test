<?php 
	
	$code = $_GET["code"];
	// echo $code;
	$appid = "wxbeba9336ac3584d0";
	$appsecret = "346b2310f20376d489eaaa0b4707794b";
	$api = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
	header("Content-Type: text/html;charset=utf-8"); 

	function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
	    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	    //验证token, 本地可以注释掉, 上线必须打开
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    $res = curl_exec($curl);
	    curl_close($curl);
	    return $res;
  	}
//1. 获取access_token和用户的openid
  	$json = httpGet($api);
	$arr = json_decode($json, true);
	$token = $arr["access_token"];
  	$open_id = $arr["openid"];
//2. 通过token和openid获取用户信息
  	//获取用户信息
  	$api = "https://api.weixin.qq.com/sns/userinfo?access_token={$token}&openid={$open_id}&lang=zh_CN";
  	$json = httpGet($api);
	$arr = json_decode($json, true);

	$openid = $arr["openid"];
	$nickname = $arr["nickname"];
	$sex = $arr["sex"];
	$province = $arr["province"];
	$city = $arr["city"];
	$country = $arr["country"];
	$headimgurl = $arr["headimgurl"];


//3. 成功之后存到数据库里
  	mysql_connect(SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
	mysql_select_db(SAE_MYSQL_DB);
	mysql_query("set names utf8");
	$sql = "SELECT * FROM wx_users WHERE openid='{$openid}'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		//存在
	}else {
		$sql = "INSERT INTO wx_users(id, openid, nickname, sex, province, city, country, headimgurl) VALUES(null, '{$openid}', '{$nickname}', '{$sex}', '{$province}', '{$city}', '{$country}', '{$headimgurl}')";
		$result = mysql_query($sql);
		var_dump($result);
		if (mysql_insert_id() > 0) {
			//插入成功
		}
	}
	//




  	echo "<img src='{$headimgurl}'>";

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>暗黑破坏神</title>
	<style type="text/css">
		img{
			width: 300px;
		}
		a{
			font-size: 2em;
		}
	</style>
</head>
<body>
	<a href=<?php echo "http://dingjz.applinzi.com/rank_list.php"; ?>>
		排行榜
	</a>
</body>
</html>
