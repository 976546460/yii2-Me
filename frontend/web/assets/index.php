<?php


//当普通微信用户向公众账号发消息时，微信服务器将POST消息的XML数据包到开发者填写的URL上。
//获取微信POST过来的xml数据
$str = file_get_contents('php://input'); //
//保存到文件
file_put_contents('request.txt',$str);



//将接收到的xml数据字符串载入对象中
$xml = simplexml_load_string($str);
$request=[];
foreach ($xml as $key=>$value){
    $request[$key]=(string)$value;
}

//$Content = $request['Content'];

$data=simpleXML_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
//遍历city对数组 得到每个城市的信息
foreach ($data->city as $city){
//    $Content= ((string)$city['cityname']).'|'.$request['Content'];
    if((string)$city['cityname']==(string)$request['Content']){
        $Content= ((string)$city['cityname'].'---'.((string)$city['stateDetailed']));
    }else{
        $Content="只限大四川的天气哦!";
    }
}

require 'text.xml';

?>