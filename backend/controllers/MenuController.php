<?php

namespace backend\controllers;

use backend\models\Menu;
use frontend\controllers\CommonController;

class MenuController extends CommonController
{
    public function actionIndex()
    {
        $model=new Menu();
        $model->getShowList();

        return $this->render('index',['model'=>$model]);
    }

    //添加
    public function actionAdd()
    {
        $model = new Menu();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->parent_id == "") { //添加分类时 选择了顶级菜单 就将顶级菜单的parent_id=0
                $model->parent_id = 0;
            }
            if ($model->save()) {
                //提示   跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);
            };
        }
        //加载视图

        return $this->render('add', ['model' => $model]);

    }


//  修改
    public function actionEdit($id)
    {
        $model = Menu::findOne($id);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if ($model->editAction($id)) {
                $model->save();
                //提示   跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        //加载视图
        return $this->render('add', ['model' => $model]);
    }
    //删除

    public function actionDelete($id){
        $result=Menu::findOne(['id'=>$id]);
        if($result){
            //存在数据不能删除  添加错误提示
           $this->error('不能删除非空的选项菜单',[['menu','index']],3);
        }else {
            Menu::deleteAll(['id' => $id]);
            \Yii::$app->session->setFlash('success','删除成功!');
           return  $this->redirect(['menu/index']);

        }
    }

}