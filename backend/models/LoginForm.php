<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 9:40
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
//记住我
    public $rememberMe;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            '$rememberMe' => '记住我',
        ];

    }

    //用户登录
    public function login()
    {
        //根据用户名查找用户
        $model = User::findOne(['username' => $this->username]);
        //查询到用户就进行密码验证
        if ($model) {
            //验证密码
            if (\Yii::$app->security->validatePassword($this->password, $model->password_hash)) {
                //密码正确  登陆
                if ($this->rememberMe) {  // 击记了住密码 login第一个参数是用户对象  第二个参数 设置cookie的过期时间
                    \Yii::$app->user->login($model,600);//秒 为单位
                } else { //没有点击记住密码 就不将登陆信息保存在cookie中 所以第二个参数就为空
                    \Yii::$app->user->login($model);
                }
                return true;
            } else {
                $this->addError('password', '密码不正确');
                return false;
            }
        } else {
            //没有查询到数据
            $this->addError('username', '用户不存在');
            return false;
        }

    }
}