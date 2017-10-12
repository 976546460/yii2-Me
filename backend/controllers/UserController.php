<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{
    public function actionIndex()
    {

//        var_dump(\Yii::$app->user->isGuest);exit;
        $model = User::find()->all();
        return $this->render('index', ['model' => $model]);
    }

    public function actionAdd()
    {
        $model = new User();
        $model->scenario = User::SCENARIO_ADD;//  应用场景的验证规则
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //在user模型中启用了beforeSave->保存之前执行的代码 在此可以直接保存
            $r = $model->save();
            if ($r != false) {
                //保存当前的用户角色
                if ($model->addUserRole($model->id)) {
                    //添加成功    创建提示信息
                    \Yii::$app->getSession()->setFlash('success', '添加成功');
                    //跳转
                    return $this->redirect(['user/index']);
                }
            } else { //添加不成功  跳转到添加页面
                return $this->render('add', ['model' => $model]);
            }
        }
        // 首次点击添加用户-》 加载视图
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        //查询数据
        $model = User::findOne(['id' => $id]);
        // 当根据用户id在数据库中没有查询到数据时，抛出一个异常
        if ($model == null) {
            throw new NotFoundHttpException('没有用户数据');
        }
        //调用user模型里面的方法将当前账户里面的角色数据写入当前model
        $model->loadDate($id);
        $oldName = $model->name;
        //判断数据提交方式和数据有效性

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //在user模型中启用了beforeSave->保存之前执行的代码 在此可以直接保存
            $r = $model->save();//返回值为beforeSave的执行结果
            if ($r != false) {
                if ($model->updateUserRole($model->id, $oldName)) {
                    //添加成功    创建提示信息
                    \Yii::$app->getSession()->setFlash('success', '修改成功');
                    //跳转
                    return $this->redirect(['user/index']);
                }
            } else { //添加不成功  跳转到添加页面
                return $this->render('add', ['model' => $model]);
            }
        }
        //加载视图页面
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDel($id)
    {
        //删除数据
        $model = User::findOne($id);
        $r = $model->delete();
        if ($r) {
            if($model->deleteUserRole($id)){ //删除用户关联角色
                //跳转到个人中心
                return $this->redirect(['user/index']);
            }
        }
    }


    //登陆认证
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //  调用模型里面的login方法
            if ($model->login()) {

                //登陆成功 提示 并跳转
                \Yii::$app->session->setFlash('success', '登陆成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionLoginOut(){
        \Yii::$app->user->logout();
       \Yii::$app->session->setFlash('success','退出成功');
        return $this->redirect(['user/login']);
    }







/*
    //登陆认证  //检测专用
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {


            //根据用户名查找用户
            $user = User::findOne(['username' => $model->username]);
//            var_dump($user);exit;
            //查询到用户就进行密码验证
            if ($user) {
                //验证密码
                if (\Yii::$app->security->validatePassword($model->password, $user->password_hash)) {
                    //密码正确  登陆
                    if ($model->rememberMe) {  // 击记了住密码 login第一个参数是用户对象  第二个参数 设置cookie的过期时间
                        \Yii::$app->user->login($user,600);//秒 为单位
                    } else { //没有点击记住密码 就不将登陆信息保存在cookie中 所以第二个参数就为空
                        \Yii::$app->user->login($user);
                    }
                    //登陆成功 提示 并跳转
                    \Yii::$app->session->setFlash('success', '登陆成功');
                    return $this->redirect(['user/test']);

                } else {
                    $model->addError('password', '密码不正确');
                }
            } else {
                //没有查询到数据
                $model->addError('username', '用户不存在');
            }

        }
        return $this->render('login', ['model' => $model]);
    }
    public function actionTest(){
        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
var_dump($identity);
// 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
        var_dump($id);
// 判断当前用户是否是游客（未认证的）  true=>未登录  false=>已登录
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($isGuest);
    }*/

}

