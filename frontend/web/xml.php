<?php

$data=simpleXML_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
    //遍历city对数组 得到每个城市的信息
    foreach ($data->city as $city){
            if((string)$city['cityname']=='成都'){
               echo ((string)$city['cityname'].'---'.((string)$city['stateDetailed']));
            }
        }

//var_dump($data);