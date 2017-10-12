<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16
 * Time: 9:54
 */

namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends BaseController
{
    //权限列表
    public function actionIndexPermission()
    {
        //实列化权限管理组件
        $model = \Yii::$app->authManager->getPermissions();

        //加载 视图
        return $this->render('index-permission', ['model' => $model]);
    }


    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->addPermission()) {
                \Yii::$app->session->setFlash('success', '权限添加成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }
        //加载视图页面
        return $this->render('add-permission', ['model' => $model]);
    }


    //修改权限
    public function actionEditPermission($name)
    {
        //首先调用权限内部方法查询$name对应的权限数据
        $permission = \Yii::$app->authManager->getPermission($name);
        //先判断权限存不存在  不存在就抛出一个404 异常
        if ($permission == null) {
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionForm();
        //调用模型中的方法将要修改的权限的值赋值给表单模型 用于回显
        $model->loadDate($permission);
        // 验证 并接收数据
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //调用模型中的updatePermission操作更新数据；
            if ($model->updatePermission($name)) {
                //跟新成功 做出提示
                \Yii::$app->session->setFlash('success', '权限修改成功');
                //跳转
                return $this->redirect(['rbac/index-permission']);
            }
        }
        //加载视图页面
        return $this->render('add-permission', ['model' => $model]);

    }

//删除
    public function actionDelPermission($name)
    {
        //找出$name对应的数据
        $permission = \Yii::$app->authManager->getPermission($name);
        if ($permission == null) {
            // 抛出错误
            throw  new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        //添加提示
        \Yii::$app->session->setFlash('success', '权限删除成功');
        //  跳转
        return $this->redirect(['rbac/index-permission']);
    }

    //添加角色
    public function actionAddRole()
    {
        $model = new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {//  通过验证 调用模型里面的方法 创建角色
            if ($model->addRole()) {
                \Yii::$app->session->setFlash('success', '创建角色成功');
                return $this->redirect(['rbac/index-role']);
            }
        }
        //加载页面
        return $this->render('add-role', ['model' => $model]);
    }

    public function actionIndexRole()
    {
        $models = \Yii::$app->authManager->getRoles();
        //加载视图页面
        return $this->render('index-role', ['models' => $models]);
    }

    //修改角色
    public function actionEditRole($name)
    {
        $role = \Yii::$app->authManager->getRole($name);
        if ($role == null) {
            throw new NotFoundHttpException('该角色不存在');
        }
        $model = new RoleForm();
        //调用方法将角色数据写入表单模型中
        $model->loadDate($role);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {//  通过验证 调用模型里面的方法 创建角色
            if ($model->updateRole($name)) {
                \Yii::$app->session->setFlash('success', '修改角色成功');
                return $this->redirect(['rbac/index-role']);
            }
        }
        //加载页面
        return $this->render('add-role', ['model' => $model]);

    }

    //删除
    public function actionDeleteRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $result = $authManager->remove($role);
        if ($result) {
            \Yii::$app->session->setFlash('success', '删除成功');
        }
        return $this->redirect(['rbac/index-role']);
    }

}

