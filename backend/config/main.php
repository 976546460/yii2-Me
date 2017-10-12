<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' =>\backend\models\User::className(),
            'enableAutoLogin' => true,//如果是基于cookie的自动登陆  这不里必须设置为true
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl'=>['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        //自定义的七牛云组件   具体代码再components/Qiniu.php中
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up.qiniu.com',//七牛云服务器所在不同地区有不同的网址 可在此进行配置
            'accessKey'=>'_7AxxZlSqc5v0qNJxnnfm_b0pgkh6yWaZPr2H_Nq',
            'secretKey'=>'FrEDTclcTD7YQrfC13xsrh36cojYNRHYSBS_el8t',
            'bucket'=>'phpyii',
            'domain'=>'http://or9rkj4hb.bkt.clouddn.com',
        ],
    ],
    'params' => $params,
];
