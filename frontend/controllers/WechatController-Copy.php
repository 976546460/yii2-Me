<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/4
 * Time: 8:53
 */

namespace frontend\controllers;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller
{
    //微信开发依赖的插件  easyWechat
    //关闭csrf验证
    public $enableCsrfValidation = false;

    //url 就是用于接受微信服务器发送的请求
    public function actionIndex()
    {

        $app = new Application(\Yii::$app->params['wechat']);

        $app->server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            //return "您好！欢迎关注我!";
            switch ($message->MsgType){
                case 'text':
                    //文本消息
                    switch ($message->Content){
                        case '成都':
                            $xml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                            foreach($xml as $city){
                                if($city['cityname'] == '成都'){
                                    $weather = $city['stateDetailed'];
                                    break;
                                }
                            }
                            return '成都的天气情况是：'.$weather;
                            break;
                        case '注册':
                            $url = Url::to(['user/register'],true);
                            return '点此注册'.$url;
                            break;
                        case '活动':
                            //回复图文消息 单图文信息
                            /*$news = new News([
                                'title'       => '十一大减价',
                                'description' => '图文信息的描述...',
                                'url'         => 'http://www.baidu.com',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                            ]);
                            return $news;*/
                            //多图文信息
                            $news1 = new News([
                                'title'       => '十一大减价',
                                'description' => '图文信息的描述...',
                                'url'         => 'http://www.baidu.com',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                            ]);
                            $news2 = new News([
                                'title'       => '流量5折',
                                'description' => '流量5折...',//在多图文信息中这个第二及以后个图文说明是没用的,将会不会被显示出来
                                'url'         => 'http://www.qq.com',
                                'image'       => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                            ]);
                            $news3 = new News([
                                'title'       => '无限流量套餐',
                                'description' => '流量5折...',
                                'url'         => 'http://www.jd.com',
                                'image'       => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
                            ]);
                            return [$news1,$news2,$news3];//多图文信息 返回的时候将是一个数组
                            break;
                    }

                    return '收到你的消息:'.$message->Content;//将刚刚收到的用户消息 原样返回
                    break;
                case 'event'://当前用户操作是一个事件,运行一下代码
                    //事件的类型   $message->Event
                    //事件的key值  $message->EventKey
                    if($message->Event == 'CLICK'){//菜单点击事件
                        if($message->EventKey == 'zxhd'){//用户点击下面菜单,通过key值判断他点的是不是最新活动,
                            $news1 = new News([//实列化一个News类 里面是展示活动简介等,配置url参数 点击后可进行跳转
                                'title'       => '十一大减价',
                                'description' => '图文信息的描述...',
                                'url'         => 'http://www.baidu.com',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                            ]);
                            $news2 = new News([
                                'title'       => '流量5折',
                                'description' => '流量5折...',
                                'url'         => 'http://www.qq.com',
                                'image'       => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                            ]);
                            $news3 = new News([
                                'title'       => '无限流量套餐',
                                'description' => '流量5折...',
                                'url'         => 'http://www.jd.com',
                                'image'       => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
                            ]);
                            return [$news1,$news2,$news3];//多图文信息,返回的是一个数组,包含多个对象
                        }
                    }

                    return '接受到了'.$message->Event.'类型事件'.'key:'.$message->EventKey;
                    break;
            }
        });


        $response = $app->server->serve();
            // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;

    }


    //设置菜单  上传服务器好了后在手动打开浏览器运行此方法 将下面Yii内部的 url地址生成实际的外链url
    public function actionSetMenu()
    {
        $app = new Application(\Yii::$app->params['wechat']);//实列化Application模型类
        $menu = $app->menu;//得到菜单对象
        $buttons = [//创建菜单中的按钮及按钮包含的操作
            [
                "type" => "click",
                "name" => "最新活动",
                "key"  => "zxhd"//当前为属性为点击时间的时候必须加上key值
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "个人中心",
                        "url"  => Url::to(['wechat/user'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => Url::to(['wechat/order'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['wechat/login'],true)
                    ],
                ],

            ],
        ];
        $menu->add($buttons);// 将按钮添加到菜单栏中
        //获取已设置的菜单（查询菜单）
        $menus = $menu->all();
        var_dump($menus);
    }

    //我的订单
    public function actionOrder()
    {
        //openid
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权机制
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        //var_dump($openid);
        //通过openid获取账号
        $member = Member::findOne(['openid'=>$openid]);
        if($member == null){
            //该openid没有绑定任何账户
            //引导用户绑定账户
            return $this->redirect(['wechat/login']);
        }else{
            //已绑定账户
            $orders = Order::findAll(['member_id'=>$member->id]);
//            var_dump($orders);
        }
    }

    //网页授权获取用户信息
    // 1 修改授权回调域名,字符串类型  位置-> 实际项目中:[开发 - 接口权限 - 网页服务 - 网页帐号 - 网页授权获取用户基本信息] 在测试号中:微信公众号测试品台--网页授权获取用户基本信息-修改
    // 2 发起网页授权->要在frontend/params.php设置回调页面地址,及选择scopes：公众平台
    // 3 创建回调页地址,->在控制器中创建回调页面地址 actionCallback()
    //个人中心
    public function actionUser()////要在frontend/params.php设置回调页面地址,及选择scopes：公众平台（snsapi_userinfo / snsapi_base）
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            $response->send();
        }
        echo '当前OpenID是:'.$openid;
    }


    //授权回调页
    public function actionCallback()//在frontend/params.php设置回调页面地址
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        //将openid放入session
        \Yii::$app->session->set('openid',$user->getId());
        return $this->redirect([\Yii::$app->session->get('redirect')]);//跳转到上次设置的地址,也就是上次请求页面;

    }

    public function actionTest()
    {
        \Yii::$app->session->removeAll();
    }


    // 绑定用户账号   将openid和用户账号绑定
    public function actionLogin()
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }

        //让用户登录，如果登录成功，将openid写入当前登录账户
        $request = \Yii::$app->request;
        if(\Yii::$app->request->isPost){
            $user = Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                //如果登录成功，将openid写入当前登录账户
                Member::updateAll(['openid'=>$openid],'id='.$user->id);
                if(\Yii::$app->session->get('redirect')) return $this->redirect([\Yii::$app->session->get('redirect')]);
                echo '绑定成功';exit;
            }else{
                echo '登录失败';exit;
            }
        }

        return $this->renderPartial('login');
    }

}
