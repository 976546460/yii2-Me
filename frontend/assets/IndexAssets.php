<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class IndexAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
    ];
    public $js = [
        'js/jquery-1.8.3.min.js',
        'js/header.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = [//在视图文件的头部加载js css
        'position' => \yii\web\View::POS_HEAD
    ];
}

