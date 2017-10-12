<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18
 * Time: 11:17
 */

namespace backend\components;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter
{
    public $nocheckAction=[];
    public function beforeAction($action)
    {
        $user=\Yii::$app->user;
        if(!in_array($action->uniqueId,$this->nocheckAction)){
            if(!$user->can($action->uniqueId)) {
                //如果用户位登陆  诱导用户登录
                if($user->isGuest){
                    return $action->controller->redirect($user->loginUrl);
                }
                throw new HttpException(403,'sorry!你没权限!');
            }
        }


        return parent::beforeAction($action);
    }

}