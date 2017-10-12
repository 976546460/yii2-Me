<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/1
 * Time: 16:48
 */

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use backend\components\RbacFilter;

class BaseController extends Controller
{
    //1.判断用户是否登陆  ===>行为过滤器
    //2.判断是否有权限执行  ====> 行为过滤器
    //beforeAction

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'nocheckAction'=>[
                    'user/login',

                ]
            ],
        ];
    }


}