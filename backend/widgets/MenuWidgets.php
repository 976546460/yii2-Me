<?php

namespace backend\widgets;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;
use \backend\models\Menu;
use yii\helpers\Url;

class MenuWidgets extends Widget
{
    public function init()
    {
        parent::init();
    }

    public  function run()
    {
        NavBar::begin([
            'brandLabel' => '后台管理系统',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['/article/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '请登陆', 'url' => Yii::$app->user->loginUrl];
        } else {
            $menuItems[] = ['label'=>'注销('.Yii::$app->user->identity->username.')','url'=>['user/login-out']];
        }
       /* $menuItems[] = ['label'=>'用户管理','items'=>[
            ['label'=>'添加用户','url'=>['admin/add']],
            ['label'=>'用户列表','url'=>['admin/index']]
        ]];*/
       $menus=Menu::find()->where(['parent_id'=>0])->all();
       foreach ($menus as $menu){
           $label=['label'=>$menu->label,'items'=>[]];

        foreach($menu->children as $child){
            if(Yii::$app->user->can($child->url)){
                $label['items'][]=['label'=>$child->label,'url'=>[$child->url]];
            }
        }
        if(!empty($label['items'])){
            $menuItems[]=$label;
        }

 }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }

}