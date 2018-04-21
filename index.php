<?php
header("Content-type:text/html;charset=utf-8");

function https_request($url, $data){
    if (empty($data)) {
        $data = null;
    }
	
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
	
    //添加如下head头就可传输大于1024字节请求
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
      
    }
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

$edkList = $_GET['edk'];
if(empty($edkList)){
	header("location: https://www.daishumed.com/index.html");
	exit();
}

$time = time();
$txt = rand(1000000000,9999999999);
$Region = "gz";
$SecretId = "AKIDCHBJH2B7SaXIrD8MAmCPfjbHk15xlGdn";
$SecretKey = "5yiGghW93nNlyQhtujMo494mJht374sU";  
$array['Action'] = "DescribeDrmDataKey";
$array['Nonce'] = $txt;
$array['Region'] = $Region;
$array['SecretId'] = $SecretId;
$array['Timestamp'] = $time;
$array['COMMON.PARAMS'] = NUll;
$array['edkList.0'] = $edkList;
//对签名ksort 排序     
ksort($array);
$text = "";
foreach($array as $k => $v){
	$text = $text."&".$k."=".$v;
}
$text = "POSTvod.api.qcloud.com/v2/index.php?".ltrim($text,"&");
$signStr = base64_encode(hash_hmac('sha1', $text, $SecretKey, true));

$arr['Nonce'] = $txt;
$arr['Region'] = $Region;
$arr['Timestamp'] = $time;
$arr['Action'] = "DescribeDrmDataKey";
$arr['SecretId'] = $SecretId;
$arr['edkList.0'] = $edkList;
$arr['COMMON_PARAMS'] = "";
$arr['Signature'] = $signStr;
$url = "https://vod.api.qcloud.com/v2/index.php";
$data = https_request($url,$arr); 
$data = json_decode($data,true);
$dkBin = base64_decode($data['data']['keyList']['0']['dk']);
echo $dkBin;
