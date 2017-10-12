<?php

namespace frontend\controllers;

use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use frontend\models\Cart;
use frontend\models\Member;
use yii\web\Controller;

class UserController extends CommonController
{
    public $layout = 'login';

    //用户注册
    public function actionRegister()
    {

        $model = new Member(['scenario'=>member::SCENARIO_REGISTER]);
//        var_dump(\Yii::$app->request->post());exit;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存之前自动调用模型中的beforeSaxe方法;;
            if ($model->save(false)) {//save保存之前不用验证已验证码,因为在validate()已经验证过了
                //提示 跳转
                $user = Member::findOne(['id' => $model->getId()]);
                \Yii::$app->user->login($user);//注册后马上登陆用户
                \Yii::$app->session->setFlash('success', '注册成功!');
                return $this->redirect(['index/index']);
            } else {
//                var_dump($model->getErrors());exit;
            }
        } else {
//            var_dump($model->getErrors());
        }
        return $this->render('register', ['model' => $model]);
    }

//用户修改密码
    public function actionUserEdit($id)
    {
        $model = Member::findOne(['id' => $id]);
        $model->scenario='userEdit';//启用场景验证

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存之前自动调用模型中的beforeSaxe方法;;
            if ($model->save()) {
                //提示 跳转
                \Yii::$app->session->setFlash('success', '注册成功!');
                return $this->redirect(['index/index']);
            } else {
//                var_dump($model->getErrors());exit;
            }
        } else {
//            var_dump($model->getErrors());
        }
        return $this->render('userEdit', ['model' => $model]);
    }


    //用户登录
    public function actionLogin()
    {
        $model = new \frontend\models\LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->UserLogin()) {//调用login方法
                //登陆成功 检查cookie中的购物车信息
                $user = \Yii::$app->user->id;//获取用户id
                //查看cookie中是否有购物车的数据
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');//查询cookie中 购物车的数据
                if ($cookie != null) {
                    $cart[] = unserialize($cookie->value);//将存入cookie中的序列化数据反序列化出来
                    foreach ($cart as $oneCart) {//遍历数组 得到的是每一个商品ID为键 商品数量为键值的数组
                        foreach (array_keys($oneCart) as $key) {//取出所有数组中的键位(商品id)组成一个新数组, 遍历出每一个商品id
                            //判断当前用户是否拥有该商品记录
                            $userCartDate = Cart::findOne(['goods_id' => $key]);//再通过查询每个商品在购物车数据表中是否存在
                            if ($userCartDate == null) {//不存在记录 添加在数据
                                $cart = new Cart();
                                //调用模型中的方法
                                $cart->insertCartData($user, $key, $oneCart[$key]);
                                /*  $cart->member_id = $user;
                                  $cart->goods_id = intval($key);
                                  $cart->amount = $oneCart[$key];
                                  $cart->save();*/
                            } else { //存在 更新购物车中商品的购买数量
                                $userCartDate->amount += intval($oneCart[$key]);
                                $userCartDate->update();
                            }
                        }
                    }
                    //存入数据库完毕 清除cookie
                    \Yii::$app->response->cookies->remove('cart');
                }
                //登陆成功了是否要跳转到其他未完成页面
                $url = \Yii::$app->request->get('url');
//var_dump(isset($a));exit;
                $this->success('登陆成功', [['首页', isset($url) ? $url : '/index/index.html']], 2);//继承自定义父类方发.直接使用提示并跳转
//               \Yii::$app->session->setFlash('success','登陆成功');
            }
        }
        return $this->render('login', ['model' => $model]);
    }


    public function actionLogout()
    {
        $user = \Yii::$app->user->logout();
        if ($user) {
            return $this->success('已安全退出', [['登陆', '/user/login.html']], 2);
        }


    }


    public function actionIndex()
    {
        var_dump(\Yii::$app->user->identity);
        var_dump(\Yii::$app->user->isGuest);
        exit;
        return $this->render('index');
    }


    //用户注册时的短信验证码处理
    public function actionSendSms()
    {

        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[34578]\d{9}$/', $tel)) {
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000, 9999);
        $result = true /*\Yii::$app->sms->setNum($tel)->setParam(['name' => $code])->send()*/
        ;//发送验证码到手机
//        $result = 1;
        if ($result) {
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_' . $tel, $code, 5 * 60);
            echo 'success' . $code;
        } else {
            echo '发送失败';
        }
    }


    //测试短信插件
    public function actionSms()
    {
        /*  //安装插件 composer require flc/alidayu
  // 配置信息
          $config = [
               'app_key'    => '23662080',
               'app_secret' => '835db4e555c3bd752ed7b5ddddb7f9c7',
               // 'sandbox'    => true,  // 是否为沙箱环境，默认false
           ];


   // 使用方法一
           $client = new Client(new App($config));
           $req    = new AlibabaAliqinFcSmsNumSend();

           $code = rand(1000,9999);

           $req->setRecNum('15808479339')//设置发给谁（手机号码）
               ->setSmsParam([
                   'name' => $code//${code}
               ])
               ->setSmsFreeSignName('京西')//设置短信签名，必须是已审核的签名
               ->setSmsTemplateCode('SMS_71975283');//设置短信模板id，必须审核通过

           $resp = $client->execute($req);
           var_dump($resp);
           var_dump($code);*/

        //调用components里面的Sms短信验证组件
        $code = rand(1000, 9999);//定义需要验证的数字保留在$code中
        /*@var   setNum-> 发送验证码的手机号码   setParam->设置要发送的验证码
         * */
        $result = \Yii::$app->sms->setNum(15808479339)->setParam(['name' => $code])->send();
        if ($result) {
            echo $code . '发送成功';
        } else {
            echo '发送失败';
        }
    }


}
